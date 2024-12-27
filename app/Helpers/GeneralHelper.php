<?php

namespace App\Helpers;

use App\Models\ExchangeRate;
use Carbon\Carbon;

class GeneralHelper
{
    /**
     * Verificar si la tasa del dia fue colocada
     * @return bool
     */
    public function checkExchange():bool
    {

        $date = Carbon::now();
        $month = $date->month;
        $year = $date->year;
        // Verificar si existe la mondea primaria
        $exchange = ExchangeRate::where('month', $month)
            ->where('year', $year)
            ->first();

        //Convertir a collection
        $rateCollect = collect($exchange->rate_info);

        //Obtner el dia en numero
        $today = Carbon::now()->dayOfMonth();

        //DEfinicar la variable en false
        $exists = false;

        //Recorrer los datos  ver si existe la tasa
        $rateCollect->map(function ($item) use ($today, &$exists) {

            if ($item['day'] == $today  && $item['usd'] == "0.00")
            {
                $exists = true;
            }
        });

        return $exists;

    }
}
