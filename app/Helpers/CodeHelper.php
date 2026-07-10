<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Client;
use App\Models\CreditNote;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class CodeHelper
{

    /**
     * Summary of generateCode
     * @param Model $model
     * @param int $nextNumber
     * @return string
     */
    public static function generateCode(Model $model, int $nextNumber):string
    {
//        Obtener los datos
        $map = self::getState();
//        Conseguir el nombre
        $modelName = get_class($model);
//        Tomar el prefijo de los datos
        $prefix = $map[$modelName] ?? 'GEN';
//        Longitud de carcater
        $length = 6;

//      Tomar el ultimo registro
        return sprintf(
            '%s-%s',
            $prefix,
            str_pad((string) $nextNumber, $length,'0', STR_PAD_LEFT)
        );


    }



    /**
     * Summary of getState
     * @return array
     */
    private static function getState():array
    {
        return [
            Client::class => config('appconfig.cliCode'),
            Category::class => config('appconfig.category'),
            Supplier::class => config('appconfig.supplier'),
            Purchase::class => config('appconfig.purchase'),
            CreditNote::class => config('appconfig.creditNote')

        ];
    }
}
