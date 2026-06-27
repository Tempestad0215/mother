<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    public function index()
    {
        return CashRegister::all();
    }

    public function store(Request $request)
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

        return CashRegister::create($data);
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
