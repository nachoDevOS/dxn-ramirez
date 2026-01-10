<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\AnamnesisItemStock;
use App\Models\Dispensation;
use App\Models\IncomeDetail;
use App\Models\ItemStock;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;

class ItemController extends Controller
{
    protected $storageController;
    public function __construct()
    {
        $this->middleware('auth');
        $this->storageController = new StorageController();
    }

    public function index()
    {
        $this->custom_authorize('browse_items');
        $categories = Item::with(['category'])
            ->whereHas('category', function($q){
                $q->where('deleted_at', null);
            })
            ->where('deleted_at', null)
            ->select('category_id')
            ->groupBy('category_id')
            ->get();

        return view('parameterInventories.items.browse', compact('categories'));
    }

    public function list(){
        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $category_id = request('category') ?? null;
        $status = request('status') ?? null;
        $user = Auth::user();

        $data = Item::with(['laboratory', 'presentation', 'fractionPresentation', 'category', 'brand', 'itemStocks'=>function($q)use($user){
                            $q->where('deleted_at', null);
                            $q->where('stock', '>', 0);
                            $q->with('itemStockFractions');
                            // ->whereRaw($user->branch_id? "branch_id = $user->branch_id" : 1);
                        }])
                        ->where(function($query) use ($search){
                            $query->whereHas('category', function($query) use($search){
                                $query->whereRaw($search ? "name like '%$search%'" : 1);
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "observation like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                            // ->OrWhereRaw($search ? "nameTrade like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        ->whereRaw($category_id? "category_id = '$category_id'" : 1)
                        ->where(function($q) use ($status){
                            if($status == '1'){
                                $q->whereHas('itemStocks', function($q){
                                    $q->where('stock', '>', 0)->where('deleted_at', null);
                                });
                            }elseif($status == '0'){
                                $q->whereDoesntHave('itemStocks', function($q){
                                    $q->where('stock', '>', 0)->where('deleted_at', null);
                                });
                            }
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('parameterInventories.items.list', compact('data'));
    }

    public function store(Request $request)
    {
        $this->custom_authorize('add_items');

        // Preparamos el valor de 'fraction' para la validación
        $request->merge(['fraction' => $request->has('fraction') ? 1 : 0]);

        // 1. Validar la petición
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,bmp,webp|max:2048',
            'fraction' => 'nullable|boolean',
            'fractionPresentation_id' => 'required_if:fraction,1|nullable|exists:presentations,id',
            'fractionQuantity' => 'required_if:fraction,1|nullable|numeric|min:0'
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe tener uno de los siguientes formatos: jpeg, jpg, png, bmp, webp.',
            'image.max' => 'La imagen no puede pesar más de 2 megabytes (MB).',
            'fractionPresentation_id.required_if' => 'La presentación de la fracción es obligatoria cuando se habilita la fracción.',
            'fractionQuantity.required_if' => 'La cantidad de la fracción es obligatoria cuando se habilita la fracción.',
            'fractionQuantity.numeric' => 'La cantidad de la fracción debe ser un número.',
            'fractionQuantity.min' => 'La cantidad de la fracción no puede ser negativa.'
        ]);

        DB::beginTransaction();
        try {
            Item::create([
                'category_id' => $request->category_id,
                'presentation_id' => $request->presentation_id,
                // 'brand_id' => $request->brand_id,
                // 'laboratory_id' => $request->laboratory_id,

                'name' => $request->name,
                // 'nameTrade' => $request->nameTrade,
                'observation' => $request->observation,

                'fraction' => $request->fraction ?? 0,
                'fractionPresentation_id' => $request->fraction ? $request->fractionPresentation_id : null,
                'fractionQuantity' => $request->fraction ? $request->fractionQuantity : null,

                'image' => $this->storageController->store_image($request->image, 'items')
            ]);

            DB::commit();
            return redirect()->route('voyager.items.index')->with(['message' => 'Ítem registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // Redirigir de vuelta con el error
            return redirect()->back()->with(['message' => 'Ocurrió un error: '.$th->getMessage(), 'alert-type' => 'error'])->withInput();
        }
    }

    public function update(Request $request, $id){
        $this->custom_authorize('edit_items');
        $request->merge(['fraction' => $request->has('fraction') ? 1 : 0]);

        // 1. Validar la petición
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,bmp,webp|max:2048',
            'fraction' => 'nullable|boolean',
            'fractionPresentation_id' => 'required_if:fraction,1|nullable|exists:presentations,id',
            'fractionQuantity' => 'required_if:fraction,1|nullable|numeric|min:0'
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe tener uno de los siguientes formatos: jpeg, jpg, png, bmp, webp.',
            'image.max' => 'La imagen no puede pesar más de 2 megabytes (MB).',
            'fractionPresentation_id.required_if' => 'La presentación de la fracción es obligatoria cuando se habilita la fracción.',
            'fractionQuantity.required_if' => 'La cantidad de la fracción es obligatoria cuando se habilita la fracción.',
            'fractionQuantity.numeric' => 'La cantidad de la fracción debe ser un número.',
            'fractionQuantity.min' => 'La cantidad de la fracción no puede ser negativa.'
        ]);

        DB::beginTransaction();
        try {
            
            $item = Item::find($id);
            $item->category_id = $request->category_id;
            $item->presentation_id = $request->presentation_id;
            // $item->brand_id = $request->brand_id;
            // $item->laboratory_id = $request->laboratory_id;
            $item->name = $request->name;
            // $item->nameTrade = $request->nameTrade;
            $item->observation = $request->observation;
            $item->status = $request->status=='on' ? 1 : 0;
            $item->fraction = $request->fraction ?? 0;
            $item->fractionPresentation_id = $request->fraction ? $request->fractionPresentation_id : null;
            $item->fractionQuantity = $request->fraction ? $request->fractionQuantity : null;

            if ($request->image) {
                $item->image = $this->storageController->store_image($request->image, 'items');
            }
            $item->update();

            DB::commit();
            return redirect()->route('voyager.items.index')->with(['message' => 'Actualizada exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // Redirigir de vuelta con el error
            return redirect()->back()->with(['message' => 'Ocurrió un error: '.$th->getMessage(), 'alert-type' => 'error'])->withInput();
        }
    }

    public function show($id)
    {
        $this->custom_authorize('read_items');

        $item = Item::with(['laboratory', 'brand', 'category'])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();

        return view('parameterInventories.items.read', compact('item'));
    }

    public function listStock($id)
    {
        $paginate = request('paginate') ?? 10;
        $status = request('status') ?? null;
        $search = request('search') ?? null;
        $data = ItemStock::with(['item.presentation', 'item.fractionPresentation', 'itemStockFractions'])
            ->where('item_id', $id)
            ->where(function($query) use ($search){
                $query->whereRaw($search ? "lote like '%$search%'" : 1);
            })
            ->where('deleted_at', null)
            ->where(function($q) use ($status){
                if($status == '1'){
                    $q->where('stock', '>', 0);
                }elseif($status == '0'){
                    $q->where('stock', '<=', 0);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate($paginate);

        return view('parameterInventories.items.itemStocks.list', compact('data'));
    }

    // public function listDispensations($id)
    // {
    //     $this->custom_authorize('read_items');
    //     $paginate = request('paginate') ?? 10;
    //     $item_id = $id;

    //     $data = Dispensation::with([
    //             'anamnesisForm.pet.person',
    //             'anamnesisForm.doctor',
    //             'anamnesisForm.dispensions.itemStock',
    //             'vaccinationRecord.pet.person',
    //             'vaccinationRecord.doctor',
    //             'vaccinationRecord.dispensions.itemStock'
    //         ])
    //         ->select(
    //             'anamnesisForm_id',
    //             'vaccinationRecord_id',
    //             DB::raw('MAX(created_at) as created_at'),
    //             DB::raw('SUM(quantity) as quantity'),
    //             DB::raw('SUM(amount) as amount')
    //         )
    //         ->whereHas('itemStock', function ($query) use ($id) {
    //             $query->where('item_id', $id);
    //         })
    //         ->whereNull('deleted_at')
    //         ->groupBy('anamnesisForm_id', 'vaccinationRecord_id')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($paginate);

    //     return view('parameterInventories.items.dispensations.list', compact('data', 'item_id'));
    // }

    public function listSales($id)
    {
        $this->custom_authorize('read_items');
        $paginate = request('paginate') ?? 10;

        // Obtenemos las ventas que incluyen el producto específico
        $sales = Sale::with([
                'person', 
                'register', 
                'saleDetails' => function($query) use ($id) {
                    $query->whereHas('itemStock', function($q) use ($id) {
                        $q->where('item_id', $id);
                    })->with('itemStock');
                }
            ])
            ->whereHas('saleDetails', function($query) use ($id) {
                $query->whereHas('itemStock', function($q) use ($id) {
                    $q->where('item_id', $id);
                });
            })
            ->where('deleted_at', null)
            ->orderBy('dateSale', 'desc')
            ->paginate($paginate);

        return view('parameterInventories.items.sales.list', compact('sales'));
    }

    public function storeStock(Request $request, $id)
    {
        $this->custom_authorize('add_items');    
        $item = Item::findOrFail($id);


        DB::beginTransaction();
        try {
            ItemStock::create([
                'item_id' => $id,
                'lote'=>$request->lote,
                'quantity' =>  $request->quantity,
                'stock' => $request->quantity,
                'pricePurchase' => $request->pricePurchase,
                'priceSale' => $request->priceSale,
                'expirationDate'=> $request->expirationDate,

                'dispensed' => $request->dispensedPrice ?'Fraccionado':'Entero',
                'dispensedQuantity'=> $item->fractionQuantity??null,
                'dispensedPrice' => $request->dispensedPrice??null,

                'type' => 'Ingreso',
                'observation' => $request->observation,
            ]);
            DB::commit();
            return redirect()->route('voyager.items.show', ['id'=>$id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('voyager.items.show',  ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        } 
    }

    public function destroyStock($id, $stock)
    {
        $item = ItemStock::where('id', $stock)
                ->where('deleted_at', null)
                ->first();
        if($item->stock != $item->quantity)
        {
            return redirect()->route('voyager.items.show', ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {            
            if($item->incomeDetail_id != null)
            {
                $incomeDetail = IncomeDetail::where('deleted_at', null)->where('id', $item->incomeDetail_id)->first();
                $incomeDetail->increment('stock', $item->quantity);
            }
            $item->delete();
            DB::commit();
            return redirect()->route('voyager.items.show', ['id'=>$id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('voyager.items.show', ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


}
