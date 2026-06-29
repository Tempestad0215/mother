<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    public function index()
    {
        return CashRegister::all();
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
        return back();
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
