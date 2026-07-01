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

        // Crear los datos
        CashRegister::create([
            'user_uuid' => Auth()->user()->uuid,
            'opening_balance' => $request->input('opening_balance'),
            'closing_balance' => 0.00,
            'status' => true,
            'expected_balance' => 0.00,
        ]);

        // Devolver hacia atras
        return redirect()->route('sale.index');
    }


    /**
     * @return Response
     */
    public function close()
    {
        $cashRegister = CashRegister::where('user_uuid', auth()->user()->uuid)
            ->with('movements')
            ->where('status', true)->first();


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

        $cashRegister->update([
            'status' => false,
            'closing_balance' => $validate['physical_cash'],
            'expected_balance' => $validate['expected_balance'],
            'closed_at' => now(),
        ]);

        return redirect()->route('sale.index');
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
