<?php

namespace App\Models;

class SaleObserver
{

    /**
     * @param Sale $sale
     * @return void
     */
    public function creating(Sale $sale):void
    {
        $activeCashRegister = CashRegister::where('user_uuid', auth()->user()->uuid)
            ->where('status', true)->first();

        if($activeCashRegister)
        {
            $sale->cash_register_uuid = $activeCashRegister->uuid;
        }else{
            // Opción alternativa: Lanzar una excepción si intentan vender sin abrir caja
            abort(403, 'No puedes realizar ventas sin tener una caja abierta.');
        }
    }

    /**
     * @param Sale $sale
     * @return void
     */
    public function created(Sale $sale): void
    {

    }

    /**
     * @param Sale $sale
     * @return void
     */
    public function updated(Sale $sale): void
    {
    }
}
