<?php

namespace App\Http\Controllers;

use App\Models\Dispensation;
use App\Models\ItemStock;
use App\Models\ItemStockFraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleTransaction;
use App\Models\Transaction;
use Symfony\Component\Console\Descriptor\ReStructuredTextDescriptor;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_sales');
        return view('sales.browse');
    }

    public function list()
    {
        $this->custom_authorize('browse_sales');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        // $status = request('status') ?? null;
        // $typeSale = request('typeSale') ?? null;

        $data = Sale::with([
            'person',
            'register',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null);
            },
            'saleDetails.itemStock.item',
            'saleTransactions' => function ($q) {
                $q->where('deleted_at', null);
            },
        ])
            ->where(function ($query) use ($search) {
                $query
                    ->OrWhereRaw($search ? "id = '$search'" : 1)
                    ->OrWhereRaw($search ? "code like '%$search%'" : 1);
            })
            ->where('deleted_at', null)
            // ->whereRaw($typeSale ? "typeSale = '$typeSale'" : 1)
            // ->whereRaw($status ? "status = '$status'" : 1)
            ->orderBy('id', 'DESC')
            ->paginate($paginate);            

        return view('sales.list', compact('data'));
    }

    public function create()
    {
        // $branches = Branch::where('deleted_at', null)->get();
        $cashier = $this->cashier(null, 'user_id = "' . Auth::user()->id . '"', 'status = "Abierta"');
        $user = Auth::user();
        $this->custom_authorize('add_sales');
        return view('sales.edit-add', compact('cashier'));
    }

    public function edit(Sale $sale)
    {
        $this->custom_authorize('edit_sales');
        $cashier = $this->cashier(null, 'user_id = "' . Auth::user()->id . '"', 'status = "Abierta"');

        $sale = Sale::with([
                'person',
                'register',
                'saleTransactions',
                'saleDetails' => function ($q) {
                    $q->where('deleted_at', null);
                },
                'saleDetails.itemStock'=>function($q){
                    $q->where('deleted_at', null);
                },
                'saleDetails.itemStock.item.category',
                'saleDetails.itemStock.item.presentation',
                'saleDetails.itemStock.item.laboratory',
                'saleDetails.itemStock.item.brand',
                'saleDetails.itemStock.itemStockFractions'=>function($q){
                    $q->where('deleted_at', null);
                },
                'saleDetails.itemStockFraction'=>function($q){
                    $q->where('deleted_at', null);
                },

                
            ])
            ->where('id', $sale->id)
            ->first();

        // return $sale;
        return view('sales.edit-add', compact('sale', 'cashier'));
    }
    
    public function generarNumeroFactura($typeSale)
    {
        $prefix = $typeSale != 'Proforma' ? 'VTA-' : 'PRO-';
        $fecha = now()->format('Ymd');
        $count = Sale::withTrashed()
            // ->where('typeSale', $typeSale)
            ->whereRaw($typeSale != 'Proforma' ? 'typeSale != "Proforma"' : 'typeSale = "Proforma"')
            ->whereDate('created_at', today())
            ->count();

        return $prefix . $fecha . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $this->custom_authorize('add_sales');

        $amount_cash = $request->amount_cash ? $request->amount_cash : 0;
        $amount_qr = $request->amount_qr ? $request->amount_qr : 0;

        if(($amount_cash + $amount_qr) < $request->amountTotalSale)
        {
            return redirect()
                ->route('sales.create')
                ->with(['message' => 'Monto Incorrecto.', 'alert-type' => 'error']);
        }
        if($request->amountTotalSale == 0)
        {
            return redirect()
                ->route('sales.create')->with(['message' => 'Ingrese una cantidad de producto valido.', 'alert-type' => 'error']);
        }

        $cashier = $this->cashier(null,'user_id = "'.Auth::user()->id.'"', 'status = "Abierta"');
        if (!$cashier) {
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }

        // =================================================================================
        // 1. Bucle de validación de stock ANTES de iniciar la transacción
        // =================================================================================
        if(!$request->products)
        {
            return redirect()
                ->route('sales.create')
                ->with(['message' => 'No se encontraron productos.', 'alert-type' => 'error']);
        }
        foreach ($request->products as $key => $value) {
            $itemStock = ItemStock::with(['item', 'itemStockFractions'])->findOrFail($value['id']);
            $quantity_unit = $value['quantity_unit'] ?? 0; // Cantidad de unidades enteras
            $quantity_fraction = $value['quantity_fraction'] ?? 0; // Cantidad de fracciones

            // Si no se vende nada de este producto, saltar
            if ($quantity_unit == 0 && $quantity_fraction == 0) {
                continue;
            }

            $total_stock_in_fractions = $itemStock->stock * ($itemStock->dispensedQuantity ?? 1); //obtenemos 

            // Lógica para stock real si es fraccionado
            if ($itemStock->dispensed === 'Fraccionado' && $itemStock->dispensedQuantity > 0) {
                $fractions_sold = $itemStock->itemStockFractions->sum('quantity'); //obtengo el total de fracciones vendidas
                // return $fractions_sold;

                if ($fractions_sold > 0) {
                    $opened_units = ceil($fractions_sold / $itemStock->dispensedQuantity); // Unidades completas que se han tenido que "abrir" para vender por fracción
                    // return $opened_units;

                    // Fracciones restantes de la última unidad abierta
                    $remaining_fractions_in_opened_units = ($opened_units * $itemStock->dispensedQuantity) - $fractions_sold; 
                    // return $remaining_fractions_in_opened_units;
        
                    // Si se vendió una unidad completa en fracciones, las restantes son 0, no la cantidad total. Usamos max() para evitar valores negativos.
                    $full_units = max(0, $itemStock->stock - $opened_units);  //obtengo el valor total entero de productos enteros
                    // return $full_units;
                    
                    //Obtenemos el total en fracciones de productos enteros
                    $total_stock_in_fractions = ($full_units * $itemStock->dispensedQuantity) + $remaining_fractions_in_opened_units;
                    // return $total_stock_in_fractions;
                }
            }

            $requested_fractions = ($quantity_unit * ($itemStock->dispensedQuantity ?? 1)) + $quantity_fraction;

            if ($requested_fractions > $total_stock_in_fractions) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with([
                        'message' => 'Stock insuficiente para el producto: ' . $itemStock->item->nameGeneric,
                        'alert-type' => 'error'
                    ]);
            }
        }
        // =================================================================================
        // Fin de la validación
        // =================================================================================
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'status' => 'Completado',
            ]);
            $sale = Sale::create([
                'person_id' => $request->person_id,
                // 'branch_id' => $request->branch_id,
                'cashier_id' => $cashier->id,

                'code' => $this->generarNumeroFactura($request->typeSale),
                'typeSale' => $request->typeSale,
                'amountReceived' => $request->amountReceived,
                'amountChange' => $request->payment_type == 'Efectivo'? $request->amountReceived-$request->amountTotalSale : 0,
                'amount' => $request->amountTotalSale,
                'observation' => $request->observation,
                'dateSale' => Carbon::now(),
                'status' => $request->typeSale == 'Venta al Contado' ? 'Pagado' : (($amount_cash+$amount_qr) >= $request->amountTotalSale?'Pagado':'Pendiente'),
            ]);

            if ($request->payment_type == 'Efectivo' || $request->payment_type == 'Efectivo y Qr') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->amountTotalSale - $amount_qr,
                    'paymentType' => 'Efectivo',
                ]);
            }
            if ($request->payment_type == 'Qr' || $request->payment_type == 'Efectivo y Qr') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $amount_qr,
                    'paymentType' => 'Qr',
                ]);
            }

            // return $request;
            // =================================================================================
            // 2. Bucle de creación de detalles y descuento de stock
            // =================================================================================
            foreach ($request->products as $key => $value) {
                $quantity_unit = $value['quantity_unit'] ?? 0;
                $quantity_fraction = $value['quantity_fraction'] ?? 0;

                // Si no se vende nada de este producto, saltar
                if ($quantity_unit == 0 && $quantity_fraction == 0) {
                    continue;
                }
                $itemStock = ItemStock::findOrFail($value['id']);

                // Lógica para venta de unidades enteras
                if ($quantity_unit > 0) {
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'itemStock_id' => $itemStock->id,
                        'dispensed' => 'Entero',
                        'pricePurchase' => $itemStock->pricePurchase,
                        'price' => $value['price_unit'],
                        'quantity' => $quantity_unit,
                        'amount' => $value['price_unit'] * $quantity_unit,
                    ]);

                    $itemStock->decrement('stock', $quantity_unit);
                }

                // return $request;

                // Lógica para venta de fracciones
                if ($quantity_fraction > 0) {
                    // Obtener el total de fracciones vendidas ANTES de la venta actual
                    $fractions_sold_before = $itemStock->itemStockFractions()->where('deleted_at', null)->sum('quantity');
                    // return $fractions_sold_before;
                        
                    // Calcular las unidades "abiertas" después de esta venta
                    $fractions_sold_after = $fractions_sold_before + $quantity_fraction;
                    // return $fractions_sold_after;
                    // $opened_units_after = $fractions_sold_after / $itemStock->dispensedQuantity;                        
                    $opened_units_after = $fractions_sold_after / ($itemStock->dispensedQuantity ?: 1);
                        
                    if($opened_units_after > $itemStock->stock){
                        DB::rollBack();
                        return  redirect()
                                ->route('sales.index')
                                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error'])->withInput();
                    }
    
                    // Si la venta de esta fracción provocó que se completara y "abriera" una nueva unidad, descontamos del stock principal
                    if ($itemStock->stock == $opened_units_after) {
                        $itemStock->decrement('stock', $opened_units_after);
                    }
                    $itemStockFraction = ItemStockFraction::create([
                        'itemStock_id' => $itemStock->id,
                        'quantity' => $quantity_fraction,
                        'price' => $value['price_fraction'],
                        'amount' => $value['price_fraction'] * $quantity_fraction,
                    ]);
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'itemStock_id' => $itemStock->id,
                        'itemStockFraction_id' => $itemStockFraction->id,
                        'dispensed' => 'Fraccionado',
                        'pricePurchase' => $itemStock->pricePurchase,
                        'price' => $value['price_fraction'],
                        'quantity' => $quantity_fraction,
                        'amount' => $value['price_fraction'] * $quantity_fraction,
                    ]);
                }
            }

            $sale = Sale::with([
                    'person',
                    'register',
                    'saleDetails' => function ($q) {
                        $q->where('deleted_at', null)->with(['itemStock.item']);
                    },
                ])
                ->where('id', $sale->id)
                ->first();
            // =================================================================================

            DB::commit();
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success', 'sale' => $sale]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function update(Request $request, Sale $sale)
    {
        $this->custom_authorize('edit_sales');

        $amount_cash = $request->amount_cash ? $request->amount_cash : 0;
        $amount_qr = $request->amount_qr ? $request->amount_qr : 0;

        if (($amount_cash + $amount_qr) < $request->amountTotalSale) {
            return redirect()
                ->route('sales.edit', ['sale' => $sale->id])
                ->with(['message' => 'Monto Incorrecto.', 'alert-type' => 'error']);
        }

        $cashier = $this->cashier(null,'user_id = "'.Auth::user()->id.'"', 'status = "Abierta"');
        if (!$cashier) {
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }
        if($cashier->id != $sale->cashier_id){
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'No puede modificar ventas de otra caja.', 'alert-type' => 'warning']);
        }
        if(!$request->products)
        {
            return redirect()
                ->route('sales.create')
                ->with(['message' => 'No se encontraron productos.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            // Eliminar transacciones de pago antiguas
            foreach ($sale->saleTransactions as $saleTransaction) {
                if ($saleTransaction->transaction) {
                    $saleTransaction->transaction->delete();
                }
                $saleTransaction->delete();
            }

            // 1. Restaurar stock y eliminar detalles existentes
            // Es más seguro eliminar y recrear los detalles para manejar correctamente 
            // los cambios entre unidades y fracciones.
            foreach ($sale->saleDetails as $detail) {
                $itemStock = ItemStock::findOrFail($detail->itemStock_id);

                if ($detail->dispensed == 'Entero') {
                    $itemStock->increment('stock', $detail->quantity);
                    $detail->delete();
                } 
                elseif ($detail->dispensed == 'Fraccionado' && $detail->itemStockFraction) {
                        // Si es fracción, eliminamos el registro de fracción (soft delete)
                    $detail->itemStockFraction->delete();

                    $itemStockUnit = SaleDetail::with(['sale'])
                        ->whereHas('sale', function ($q) {
                            $q->where('deleted_at', null);
                        })
                        ->where('itemStock_id',$detail->itemStock_id)
                        ->where('dispensed','Entero')
                        ->where('deleted_at', null)
                        ->get()->sum('quantity');
                  
                    $itemStock->update([
                        'stock' => $itemStock->quantity
                    ]);
                    $itemStock->decrement('stock', ($itemStockUnit));
                    $detail->delete();   

                }
            }

            $sale->update([
                'person_id' => $request->person_id,
                'amountReceived' => $request->amountReceived,
                'amountChange' => $request->payment_type == 'Efectivo' ? $request->amountReceived - $request->amountTotalSale : 0, // Ajustar si es necesario

                'amount' => $request->amountTotalSale,
                'observation' => $request->observation,
                'status' => $request->typeSale == 'Venta al Contado' ? 'Pagado' : (($amount_cash + $amount_qr) >= $request->amountTotalSale ? 'Pagado' : 'Pendiente'),
            ]);

            // 2. Crear nuevos detalles (Lógica idéntica a store)
            foreach ($request->products as $key => $value) {
                $quantity_unit = $value['quantity_unit'] ?? 0;

                $quantity_fraction = $value['quantity_fraction'] ?? 0;

                if ($quantity_unit == 0 && $quantity_fraction == 0) {
                    continue;
                }

                $itemStock = ItemStock::findOrFail($value['id']);

                // Venta de Unidades
                if ($quantity_unit > 0) {
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'itemStock_id' => $itemStock->id,
                        'dispensed' => 'Entero',
                        'pricePurchase' => $itemStock->pricePurchase,
                        'price' => $value['price_unit'],
                        'quantity' => $quantity_unit,
                        'amount' => $value['price_unit'] * $quantity_unit,
                    ]);

                    $itemStock->decrement('stock', $quantity_unit);
                }

                // Lógica para venta de fracciones
                if ($quantity_fraction > 0) {
                    // Obtener el total de fracciones vendidas ANTES de la venta actual
                    $fractions_sold_before = $itemStock->itemStockFractions()->where('deleted_at', null)->sum('quantity');
                    // return $fractions_sold_before;
                        
                    // Calcular las unidades "abiertas" después de esta venta
                    $fractions_sold_after = $fractions_sold_before + $quantity_fraction;
                    // return $fractions_sold_after;
                    // $opened_units_after = $fractions_sold_after / $itemStock->dispensedQuantity;                        
                    $opened_units_after = $fractions_sold_after / ($itemStock->dispensedQuantity ?: 1);
                        
                    if($opened_units_after > $itemStock->stock){
                        DB::rollBack();
                        return  redirect()
                                ->route('sales.index')
                                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error'])->withInput();
                    }
    
                    // Si la venta de esta fracción provocó que se completara y "abriera" una nueva unidad, descontamos del stock principal
                    if ($itemStock->stock == $opened_units_after) {
                        $itemStock->decrement('stock', $opened_units_after);
                    }
                    $itemStockFraction = ItemStockFraction::create([
                        'itemStock_id' => $itemStock->id,
                        'quantity' => $quantity_fraction,
                        'price' => $value['price_fraction'],
                        'amount' => $value['price_fraction'] * $quantity_fraction,
                    ]);
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'itemStock_id' => $itemStock->id,
                        'itemStockFraction_id' => $itemStockFraction->id,
                        'dispensed' => 'Fraccionado',
                        'pricePurchase' => $itemStock->pricePurchase,
                        'price' => $value['price_fraction'],
                        'quantity' => $quantity_fraction,
                        'amount' => $value['price_fraction'] * $quantity_fraction,
                    ]);
                }
            }


            // Crear nuevas transacciones de pago
            $transaction = Transaction::create([
                'status' => 'Completado',
            ]);

            if ($request->payment_type == 'Efectivo' || $request->payment_type == 'Efectivo y Qr') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->amountTotalSale - $amount_qr,
                    'paymentType' => 'Efectivo',
                ]);
            }
            if ($request->payment_type == 'Qr' || $request->payment_type == 'Efectivo y Qr') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $amount_qr,
                    'paymentType' => 'Qr',
                ]);
            }


            $sale = Sale::with([
                'person',
                'register',
                'saleDetails' => function ($q) {
                    $q->where('deleted_at', null)->with(['itemSale']);
                },
            ])
                ->where('id', $sale->id)
                ->first();
            DB::commit();
            return redirect()->route('sales.index')->with(['message' => 'Venta actualizada exitosamente.', 'alert-type' => 'success', 'sale' => $sale]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('sales.edit', ['sale' => $sale->id])->with(['message' => 'Ocurrió un error al actualizar: ' . $e->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function prinf($id)
    {
        $sale = Sale::with([
            'person',
            'register',
            'branch',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null);
            },
        ])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();
        if ($sale->typeSale != 'Proforma') {
            $transaction = SaleTransaction::with(['transaction'])
                ->where('sale_id', $sale->id)
                ->first();
            return view('sales.prinfSale', compact('sale', 'transaction'));
        } else {
            return view('sales.prinfProforma', compact('sale'));
        }
    }

    public function destroy($id)
    {
        $sale = Sale::with([
                'saleDetails' => function ($q) {
                    $q->where('deleted_at', null);
                },
                'saleDetails.itemStockFraction' => function ($q) {
                    $q->where('deleted_at', null);
                }
            ])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();

        $cashier = $this->cashier(null,'user_id = "'.Auth::user()->id.'"', 'status = "Abierta"');
        if (!$cashier) {
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }
        if($cashier->id != $sale->cashier_id){
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'No puede eliminar ventas de otra caja.', 'alert-type' => 'warning']);
        }

        DB::beginTransaction();
        try {

            foreach ($sale->saleDetails as $detail) {
                $itemStock = ItemStock::findOrFail($detail->itemStock_id);

                if ($detail->dispensed == 'Entero') {
                    $itemStock->increment('stock', $detail->quantity);
                    $detail->delete();
                } 
                elseif ($detail->dispensed == 'Fraccionado' && $detail->itemStockFraction) {
                        // Si es fracción, eliminamos el registro de fracción (soft delete)
                    $detail->itemStockFraction->delete();

                    $itemStockUnit = SaleDetail::with(['sale'])
                        ->whereHas('sale', function ($q) {
                            $q->where('deleted_at', null);
                        })
                        ->where('itemStock_id',$detail->itemStock_id)
                        ->where('dispensed','Entero')
                        ->where('deleted_at', null)
                        ->get()->sum('quantity');

                    $itemStock->update([
                        'stock' => $itemStock->quantity
                    ]);
                    $itemStock->decrement('stock', ($itemStockUnit));
                    $detail->delete();   
                }
            }


            $sale->delete();
            DB::commit();
            return redirect()->back()->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success'])->withInput();
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error'])->withInput();

        }
    }


    public function show($id)
    {
        $sale = Sale::with([
            'person',
            'register',
            'saleTransactions',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null);
            },
            'saleDetails.itemStock.item' => function ($q) {
                $q->withTrashed();
            },
        ])
            ->where('id', $id)
            ->withTrashed()
            ->first();

        return view('sales.read', compact('sale'));
    }



  

}
