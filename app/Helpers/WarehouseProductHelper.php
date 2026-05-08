<?php

namespace App\Helpers;

use App\Dtos\ProductWarehouseDto;
use App\Models\WarehouseProduct;

class WarehouseProductHelper
{
    public static function create(array $data,bool $is_service = false):void
    {
//        No hacer nada si es servicios
        if($is_service){
            return;
        }

//        si no hay datos pues se deja vacio
        if(empty($data)){
            return;
        }

//        Convertir los datos
        foreach ($data as $item){
            WarehouseProduct::create($item);
        }

    }
}
