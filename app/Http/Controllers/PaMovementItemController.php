<?php

namespace App\Http\Controllers;

use App\Models\PaMovementItem;
use Illuminate\Http\Request;

class PaMovementItemController extends Controller
{
    public function index()
    {
        return PaMovementItem::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_uuid' => ['required'],
            'warehouse_uuid' => ['required'],
            'cost' => ['required', 'decimal:2'],
            'quantity' => ['required', 'decimal:2'],
            'tax' => ['required'],
            'tax_uuid' => ['required'],
            'amount' => ['required'],
        ]);

        return PaMovementItem::create($data);
    }

    public function show(PaMovementItem $paMovementItem)
    {
        return $paMovementItem;
    }

    public function update(Request $request, PaMovementItem $paMovementItem)
    {
        $data = $request->validate([
            'product_uuid' => ['required'],
            'warehouse_uuid' => ['required'],
            'cost' => ['required', 'decimal:2'],
            'quantity' => ['required', 'decimal:2'],
            'tax' => ['required'],
            'tax_uuid' => ['required'],
            'amount' => ['required'],
        ]);

        $paMovementItem->update($data);

        return $paMovementItem;
    }

    public function destroy(PaMovementItem $paMovementItem)
    {
        $paMovementItem->delete();

        return response()->json();
    }
}
