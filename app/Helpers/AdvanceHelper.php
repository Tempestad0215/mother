<?php

namespace App\Helpers;

use App\Models\Advance;
use App\Models\Credit;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;

class AdvanceHelper
{
    public function store(Request $request, int $client_id)
    {

        //Verificar si existe en credito
        $existsCredit = Credit::where('status', false)
            ->where('client_id', $client_id)->first();

        if(!$existsCredit)
        {
            $advance = new Advance();

            //Guardar los datos
            $advance->client_id = $client_id;
            $advance->amount = $request->get('advance_amount');
            $advance->date = $request->get('advance_date');
            $advance->expire = $request->get('advance_expire');
            $advance->balance += $request->get('advance_amount');

            //Guardar los datos
            $advance->save();
        }else{

            throw new HttpClientException('Este Cliente Esta Registrado Como Credito');
        }





    }
}
