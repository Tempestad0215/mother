<?php

namespace App\Services;

use App\Models\Setting;

class configService
{
    protected Setting $configuractions;

    /**
     * llamar el metodo principal
     */
    public function __construct()
    {
        $this->configuractions = Setting::firstOrFail();
    }


    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function get($key, $default = null):mixed
    {
        return $this->configuractions->{$key} ?? $default;
    }

}
