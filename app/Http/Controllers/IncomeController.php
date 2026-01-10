<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\IncomeTransaction;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    protected $storageController;
    public function __construct()
    {
        $this->middleware('auth');
        $this->storageController = new StorageController();
    }

    public function index()
    {
        $this->custom_authorize('browse_incomes');
        return view('administrations.incomes.browse');
    }

    public function list(){
        $this->custom_authorize('browse_incomes');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $typeIncome = request('typeIncome') ?? null;
        $status = request('status') ?? null;

        $data = Income::with(['register', 'supplier', 'incomeDetails'=>function($q){
                            $q->where('deleted_at', null);
                        }, 'incomeTransactions'=>function($q){
                            $q->where('deleted_at', null);
                        }])
                        ->withSum(['incomeTransactions as amortization' => function($query) {
                            $query->where('deleted_at', null);
                        }], 'amount')
                       
                        ->where(function($query) use ($search){
                            $query->OrwhereHas('supplier', function($query) use($search){
                                $query->whereRaw($search ? "name like '%$search%'" : 1);
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "observation like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        ->when($typeIncome, function ($query, $typeIncome) {
                            return $query->where('typeIncome', $typeIncome);
                        })
                        ->when($status, function ($query, $status) {
                            if ($status == 'si') {
                                return $query->whereHas('incomeDetails', fn($q) => $q->where('stock', '>', 0));
                            }
                            return $query->whereDoesntHave('incomeDetails', fn($q) => $q->where('stock', '>', 0));
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);
        return view('administrations.incomes.list', compact('data'));
    }

    public function create()
    {
        $this->custom_authorize('browse_incomes');
        $suppliers = Supplier::where('deleted_at', null)->get();
        return view('administrations.incomes.edit-add', compact('suppliers'));
    }

    public function store(Request $request)
    { 
        $this->custom_authorize('add_incomes');
        $amount  =  $request->amount_cash + $request->amount_qr;


        if($request->typeIncome == 'Compra al Contado')
        {
            if($amount < $request->amountTotalSale)
            {
                return redirect()->route('incomes.create')->with(['message' => 'Monto Incorrecto.', 'alert-type' => 'error']);
            }
        }

        $imageObj = new StorageController;

        $file = $request->file('file');
        
        if($file)
        {
            $file = $imageObj->file($file, "income/file");
        }

        DB::beginTransaction();
        try {         
            $income = Income::create([
                    'supplier_id'=>$request->supplier_id,
                    'typeIncome'=>$request->typeIncome, 
                    'amount'=>$request->amountTotalSale,

                    'observation'=>$request->observation,
                    'date'=>$request->date,
                    'file'=>$file,
                    'status'=>$request->typeIncome == 'Compra al Contado'?'Pagado':($amount > $request->amountTotalSale?'Pagado':'Pendiente'),
            ]);
  
            $transaction = Transaction::create([
                'status'=>'Completado'
            ]);

            $cash = $request->amountPayment - $request->amountTotalSale;
        
            if ($request->payment_type == 'Efectivo' || $request->payment_type == 'Efectivo y Qr') {
                IncomeTransaction::create([
                    'income_id' => $income->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->typeIncome == 'Compra al Contado' && $request->payment_type == 'Efectivo' ? $request->amount_cash-$cash: $request->amount_cash,
                    'paymentType' => 'Efectivo',
                ]);
            }
            if ($request->payment_type == 'Qr' || $request->payment_type == 'Efectivo y Qr') {
                IncomeTransaction::create([
                    'income_id' => $income->id,
                    'transaction_id' => $transaction->id,
                    'amount' =>  $request->typeIncome == 'Compra al Contado'? $request->amount_qr:$request->amount_qr-$cash,
                    'paymentType' => 'Qr',
                ]);
            }

            foreach ($request->products as $key => $value) {      
                $detail = IncomeDetail::create([
                    'income_id'=>$income->id,
                    'item_id'=>$value['id'],
                    'lote'=>$value['lote'],
                    'quantity'=>$value['quantity'],

                    'pricePurchase'=>$value['pricePurchase'],
                    'priceSale'=>$value['priceSale'],

                    'amountPurchase'=>$value['amountPurchase'],
                    'amountSale'=>$value['amountSale'],

                    'dispensed'=> 'Entero',
                    'expirationDate'=> $value['expirationDate'],

                    'stock'=>$value['quantity'],
                ]);
                if(isset($value['priceSaleFraction']) && isset($value['amountSaleFraction']))
                {
                    $item = Item::where('id', $value['id'])->first();
                    $detail->update([
                        'dispensed'=> 'Fraccionado',
                        'dispensedQuantity'=> $item->fractionQuantity,
                        'dispensedPrice'=> $value['priceSaleFraction'],
                    ]);
                }
            }            
            DB::commit();
            return redirect()->route('incomes.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack(); 
            return redirect()->route('incomes.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $income = Income::with(['register', 'supplier', 'incomeDetails'=>function($q){
                            $q->where('deleted_at', null)
                            ->with(['item.laboratory', 'item.brand', 'item.fractionPresentation']);
                        }, 'incomeTransactions'=>function($q){
                            $q->where('deleted_at', null)
                            ->orderBy('id', 'DESC');
                        }])
                        ->withSum(['incomeTransactions as amortization' => function($query) {
                            $query->where('deleted_at', null);
                        }], 'amount')
                        ->where('deleted_at', NULL)
                        ->where('id', $id)
                        ->first();
        $history = ItemStock::with(['item', 'incomeDetail'=>function($q){
                            $q->where('deleted_at', null);
                        }, 'incomeDetail.income'])

                        ->whereHas('incomeDetail.income', function ($query) use ($income) {
                            $query->where('id', $income->id);
                        })
                        // ->where('deleted_at', null)
                        ->orderBy('id', 'DESC')
                        ->get();
  

        return view('administrations.incomes.read', compact('income', 'history'));
    }

    public function destroy($id)
    {
        $income = Income::with(['incomeDetails' => function($q){
                $q->where('deleted_at', null);
            }])
            ->where('deleted_at', null)
            ->where('id',$id)
            ->first();
        foreach ($income->incomeDetails as $item) {
            if($item->stock != $item->quantity)
            {
                return redirect()->route('incomes.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }
     
        DB::beginTransaction();
        try {        
           
            $income->delete();
            DB::commit();
            return redirect()->route('incomes.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('incomes.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
    public function downloadFile($id)
    {
        $filePath = Income::where('id', $id)->first();
        return Storage::download($filePath->file);
    }
    public function fileStore(Request $request, $id)
    {
        $income = Income::where('id', $id)->first();
        $imageObj = new StorageController;

        $file = $request->file('file');
        
        if($file)
        {
            $file = $imageObj->file($file, "income/file");            
        }
        else
        {
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            $income->update([
                'file'=>$file
            ]);
            DB::commit();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        

    }
    public function storePayment(Request $request, $id)
    { 
        $this->custom_authorize('add_incomes');
        $income = Income::where('id', $id)->where('deleted_at', null)->first();
        $incomeTransaction = IncomeTransaction::where('income_id', $income->id)->where('deleted_at', null)->get();
        $totalPayment = $incomeTransaction->sum('amount');
        $debt = $income->amount-$totalPayment;
        if($request->amount > $debt)
        {
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {                
                $transaction = Transaction::create([
                    'type'=>$request->payment_type,
                    'status'=>'Aceptada'
                ]);
                IncomeTransaction::create([
                    'transaction_id'=>$transaction->id,
                    'income_id'=>$income->id,
                    // 'cashier_id'=>$cashier->id,
                    // 'branch_id'=>$user->branch_id,
                    'amount'=>$request->amount,
                    'observation'=>$request->observation
                ]);  

                if($totalPayment+$request->amount == $income->amount)
                {
                    $income->update(['status'=>'Pagado']);
                }
            DB::commit();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function transferIncomeDetail(Request $request, $id)
    {
        $this->custom_authorize('add_incomes');
        $income = Income::with(['incomeDetails'])
                    ->where('id', $id)
                    ->where('deleted_at', null)
                    ->first();
        foreach ($request->products as $item) {
            if($item['quantity']>0 && $item['quantity'] != NULL)
            {
                $stock = $income->incomeDetails->where('id', $item['id'])->first();
                if($item['quantity'] > $stock->stock )
                {
                    return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
                }
            }
        }
        DB::beginTransaction();
        try {      
            foreach ($request->products as $item) {
                if($item['quantity']>0)
                {
                    $detail = ItemStock::create([
                        'item_id'=>$item['item'],
                        'incomeDetail_id'=>$item['id'],

                        'quantity'=>$item['quantity'],
                        'stock'=>$item['quantity'],
                        'lote'=>$item['lote'],
                        'expirationDate'=> $item['expirationDate'],

                        'pricePurchase'=>$item['pricePurchase'],
                        'priceSale'=>$item['priceSale'],
                        'dispensed'=> $item['dispensed'],

                        'type'=>'Ingreso',
                        'observation'=>'Ingresados mediante compras'
                    ]);
                    if(isset($item['dispensedPrice']) && isset($item['dispensedQuantity']))
                    {
                        $detail->update([
                            'dispensedPrice'=> $item['dispensedPrice'],
                            'dispensedQuantity'=> $item['dispensedQuantity'],
                        ]);
                    }
                    $income->incomeDetails->where('id', $item['id'])->first()->decrement('stock', $item['quantity']);
                }
            }
            DB::commit();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroyTransferIncomeDetail($id, $transfer)
    {
        $item = ItemStock::with(['item'])
                ->where('id', $transfer)
                ->where('deleted_at', null)
                ->first();
        if($item->stock != $item->quantity)
        {
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {            
            $incomeDetail = IncomeDetail::where('deleted_at', null)->where('id', $item->incomeDetail_id)->first();
            $incomeDetail->increment('stock', $item->quantity);
            $item->delete();

            DB::commit();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('incomes.show', ['income' => $id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


}
