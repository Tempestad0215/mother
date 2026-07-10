<?php

namespace App\Http\Middleware;

use App\Models\Sequence;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CheckSequenceExistsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
//        Tomar la configuracion
        $setting = Setting::getGlobal();

        if($request->isMethod('get')){
            //        Verificar si existe la secuencia
            $sequence = Sequence::where('status', true)->exists();

            // Verificar si la configuración exige secuencias pero no hay ninguna activa
            if ($setting && $setting->sequence && !$sequence
                && !Route::is('sequence.create')
                && !Route::is('setting.index')
                && !Route::is('login')
            ) {
                return redirect()->route('sequence.create');
            }
        }

//        Continuar con el flujo normal
        return $next($request);
    }
}
