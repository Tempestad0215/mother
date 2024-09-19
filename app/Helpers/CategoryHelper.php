<?php

namespace App\Helpers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryHelper
{

//    public function getCategories(Request $request)
//    {
//        //tomar los datos de busqueda
//        $search = $request->get('search');
//
//        //Devolver los datos
//        return Category::where('name', 'like', '%' . $search . '%')
//    }

    /**
     * @return Collection
     */
    public function getAllCategories()
    {

        //Devolver todas las categoria
        return Category::all();
    }

}
