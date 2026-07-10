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
        // Obtener la caja activa del usuario
        $activeCashRegister = CashRegister::getActiveForUser(auth()->user()->uuid);

        // Asignar la caja activa al objeto Sale
        if($activeCashRegister)
        {
            // Asignar el UUID de la caja activa al campo cash_register_uuid
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
