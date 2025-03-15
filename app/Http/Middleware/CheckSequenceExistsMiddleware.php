<?php

namespace App\Http\Middleware;

use App\Models\Sequence;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckSequenceExistsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
//        Tomar la configuracion
        $setting = Setting::first();

//        Verificar si existe la secuencia
        $sequence = Sequence::where('status', true)->exists();

//        Verificar si existe alguna secuancia
        if($setting && $setting->sequence && !$sequence && $request->isMethod('get') && !Route::is('sequence.create') ){
            return redirect()->route('sequence.create');
        }
//        Continuar con el flujo normal
        return $next($request);
    }
}
