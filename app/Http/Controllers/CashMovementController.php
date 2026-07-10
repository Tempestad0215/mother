<?php

namespace App\Http\Controllers;

use App\Models\CashMovement;
use Illuminate\Http\Request;

class CashMovementController extends Controller
{
    public function index()
    {
        return CashMovement::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cash_register' => ['required'],
            'type' => ['required'],
            'amount' => ['required', 'decimal:2'],
            'concept' => ['required'],
            'comment' => ['required'],
        ]);

        return CashMovement::create($data);
    }

    public function show(CashMovement $cashMovement)
    {
        return $cashMovement;
    }

    public function update(Request $request, CashMovement $cashMovement)
    {
        $data = $request->validate([
            'cash_register' => ['required'],
            'type' => ['required'],
            'amount' => ['required', 'decimal:2'],
            'concept' => ['required'],
            'comment' => ['required'],
        ]);

        $cashMovement->update($data);

        return $cashMovement;
    }

    public function destroy(CashMovement $cashMovement)
    {
        $cashMovement->delete();

        return response()->json();
    }
}
