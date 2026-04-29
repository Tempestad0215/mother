<?php

namespace App\Helpers;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class CodeHelper
{

    public static function generateCode(Model $model, int $nextNumber)
    {
//        Obtener los datos
        $map = self::getState();
//        Conseguir el nombre
        $modelName = get_class($model);
//        Tomar el prefijo de los datos
        $prefix = $map[$modelName] ?? 'GEN';
//      Tomar el ultimo registro


    }


    private function getState():array
    {
        return [
            Client::class => config('appconfig.cliCode'),

        ];
    }
}
