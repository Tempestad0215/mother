<?php

namespace App\Services;

use App\Models\Setting;

class configService
{
    protected static ?Setting $configuractions = null;

    /**
     * Para verificar si existe algo diferente
     * @return void
     */
    public static function init(): void
    {
        //Verificar si la configuracion es igual a nulo
        if (self::$configuractions == null)
        {
            self::$configuractions = Setting::firstOrFail();
        }

    }


    /**
     * llamar los datos solictiado
     * @param string $key
     * @param $default
     * @return mixed
     */
    public static function get(string $key, $default = null):mixed
    {
        //llamar el metodo de init
        self::init();
        //Devolver los datos solicitado por el servicios
        return self::$configuractions->{$key} ?? $default;
    }

}
