<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $cashier = $this->cashierMoney(null, 'user_id = "'.Auth::user()->id.'"', 'status = "Abierta"')->original;

        if (!$cashier) {
            return redirect()
                ->back()
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }

        if($request->details == null)
        {
            return redirect()->back()->with(['message' => 'Lista vacia de gastos.', 'alert-type' => 'warning']);
        }

        $total_amount = 0;
        if($request->details){
            for ($i=0; $i < count($request->details); $i++) { 
                $total_amount += $request->quantities[$i] * $request->prices[$i];
            }
        } else {
            $total_amount = $request->amount;
        }

        if($cashier['amountCashier'] < $total_amount)
        {
            return redirect()
                ->back()
                ->with(['message' => 'No cuenta con monto en efectivo disponible para realizar un gasto.', 'alert-type' => 'warning']);
        }

        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request->details); $i++) { 
                Expense::create([
                    // 'categoryExpense_id' => $request->categoryExpense_id,
                    'observation' => $request->details[$i],
                    'quantity' => $request->quantities[$i],
                    'price' => $request->prices[$i],
                    'amount' => $request->quantities[$i] * $request->prices[$i],
                    'cashier_id' => $cashier['cashier']->id,
                ]);
            }
            DB::commit();
            return redirect()
                ->back()
                ->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->logError($th, $request);
            return redirect()
                ->back()
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy(Request $request, Expense $expense)
    {
        // $this->custom_authorize('delete_expenses');
        if ($expense->cashier->status != 'Abierta') {
            return redirect()
                ->back()
                ->with(['message' => 'No se puede eliminar un gasto de una caja que no está abierta.', 'alert-type' => 'warning']);
        }

        DB::beginTransaction();
        try {
            $expense->delete();
            DB::commit();
            return redirect()
                ->back()
                ->with(['message' => 'Gasto eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->logError($th, $request);
            return redirect()
                ->back()
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
