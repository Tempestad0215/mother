<?php

namespace App\Helpers;

use App\Models\Clients;
use Illuminate\Http\Request;

class ClientHelper
{
    /**
     * Devolver los datos del cliente
     *
     * @param Request $request
     * @return mixed
     */
    public  function  get(Request $request)
    {

        //conseguir los datos del cliente
        return Clients::where('status', false)
            ->where('name','LIKE','%'.$request->get('search').'%' )
            ->latest()
            ->simplePaginate(15);

    }
}
