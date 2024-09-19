<?php

namespace App\Helpers;

use App\Models\Credit;
use Illuminate\Http\Request;

class CreditHelper
{
    public function store(Request $request, int $client_id)
    {
        //Crear la instancia
        $credit = new Credit();

        //Guardar los datos
        $credit->client_id = $client_id;
        $credit->limit_amount = $request->get('credit_limit');
        $credit->limit_day = $request->get('credit_day');
        $credit->balance = $request->get('credit_limit');

        //Guardar los datos
        $credit->save();

    }
}
