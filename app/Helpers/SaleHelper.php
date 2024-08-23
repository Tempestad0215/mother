<?php

namespace App\Helpers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleHelper
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getSalePagination(Request $request)
    {
        //Tomar los datos de busqueda
        $search = $request->get('search');

        return Sale::where('client_name','like','%'.$search.'%')
            ->orWhere('tax','like','%'.$search.'%')
            ->orWhere('sub_total','like','%'.$search.'%')
            ->orWhere('amount','like','%'.$search.'%')
            ->latest()
            ->simplePaginate(15);

    }


}
