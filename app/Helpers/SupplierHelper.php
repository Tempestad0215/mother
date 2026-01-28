<?php

namespace App\Helpers;

use App\Enums\PurchaseStatusEnum;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierHelper
{


    /**
     * @param Request $request
     * @return mixed
     */
    public static function getReceiving(Request $request):mixed
    {

        //Tomar los datos de búsqueda
        $search = trim($request->get('search'));


        //Devolver los datos paginado a 15
        $suppliers = Supplier::query()
            ->whereHas('purchase', function ($q) {
                $q->where('status', PurchaseStatusEnum::Pendiente);
            })
            ->where('status', true)
            ->when(trim($search) !== '', function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('company_name', 'ilike', '%'.$search.'%')
                        ->orWhere('contact', 'ilike', '%'.$search.'%')
                        ->orWhere('email', 'ilike', '%'.$search.'%');
                });
            })
            ->latest('created_at')
            ->take(15)
            ->get();


        return SupplierResource::collection($suppliers);

    }


}
