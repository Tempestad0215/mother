<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashRegisterCloseResource;
use App\Models\CashRegister;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CashRegisterController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {

        return Inertia::render('CashRegister/OpenView');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        // Validar los datos
        $request->validate([
            'opening_balance' => ['required', 'numeric'],
        ]);

        // Crear los dates
        CashRegister::create([
            'user_uuid' => Auth()->user()->uuid,
            'opening_balance' => $request->input('opening_balance'),
            'closing_balance' => 0.00,
            'opened_at' => now(),
            'closed_at' => null,
            'status' => true,
            'expected_balance' => 0.00,
        ]);

        // Devolver hacia atras
        return redirect()->route('sale.index');
    }


    /**
     * @return Response|RedirectResponse
     */
    public function close(): Response|RedirectResponse
    {
        $cashRegister = CashRegister::where('user_uuid', auth()->user()->uuid)
            ->with('movements')
            ->where('status', true)->first();

        if(!$cashRegister)
        {
            return redirect()->route('cash-register.index');
        }


        return Inertia::render('CashRegister/CloseView',[
            'cashRegister' => new CashRegisterCloseResource($cashRegister),
        ]);
    }

    public function closeStore(Request $request, CashRegister $cashRegister)
    {
        $validate = $request->validate([
            'physical_cash' => ['required', 'numeric'],
            'expected_balance' => ['required', 'numeric'],
        ]);

        // 1. Obtener totales de ventas agrupados por método de pago para esta caja
        $salesByMethod = $cashRegister->sales()
            ->selectRaw('type_payment, SUM(amount) as total')
            ->groupBy('type_payment')
            ->pluck('total', 'type_payment');

        // 2. Obtener movimientos manuales
        $manualIncomes = $cashRegister->movements()->where('type', 'income')->sum('amount');
        $cashExpenses = $cashRegister->movements()->where('type', 'expense')->sum('amount');
        $vaultDeliveries = $cashRegister->movements()->where('type', 'vault_transfer')->sum('amount');

        // 3. Estructurar el snapshot para auditoría e impresión rápida

        $summary = [
            'opening_fund'     => $cashRegister->opening_balance,
            'cash_sales'       => $salesByMethod->get('cash', 0),
            'manual_incomes'   => $manualIncomes,
            'cash_expenses'    => $cashExpenses,
            'vault_deliveries' => $vaultDeliveries,
            'card_total'       => $salesByMethod->get('card', 0),
            'transfer_total'   => $salesByMethod->get('transfer', 0),
            'credit_total'     => $salesByMethod->get('credit', 0),
            'cheque_total'     => $salesByMethod->get('cheque', 0),
        ];

        $cashRegister->update([
            'status' => false,
            'closing_balance' => $validate['physical_cash'],
            'expected_balance' => $validate['expected_balance'],
            'summary' => $summary,
            'closed_at' => now(),
        ]);


        Inertia::flash([
            'cashRegisterUuid' => $cashRegister->uuid,
        ]);

        return redirect()->route('dashboard');
    }

    public function show(CashRegister $cashRegister)
    {
        return $cashRegister;
    }

    public function update(Request $request, CashRegister $cashRegister)
    {
        $data = $request->validate([
            'user_uuid' => ['required'],
            'opening_balance' => ['required', 'decimal:2'],
            'closing_balance' => ['required', 'decimal:2'],
            'expected_balance' => ['required', 'decimal:2'],
            'status' => ['boolean'],
            'opened_at' => ['required', 'date'],
            'closed_at' => ['required', 'date'],
        ]);

        $cashRegister->update($data);

        return $cashRegister;
    }

    public function destroy(CashRegister $cashRegister)
    {
        $cashRegister->delete();

        return response()->json();
    }
}
