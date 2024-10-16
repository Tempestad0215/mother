<?php

namespace App\Helpers;



use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserHelper
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getUserPaginator(Request $request)
    {

        //Sacar los datos de busqueda
        $search = $request->get('search');

        // Devolver los datos paginado
        $data = User::where('status', true)
            ->where(function ($query) use ($search) {
                $query->where('name','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%');
            })->where('id', '!=', auth()->user()->id)
            ->latest()
            ->simplePaginate(15);

        //Formatear los datos
        return USerresource::collection($data)->response()->getData(true);
    }

}
