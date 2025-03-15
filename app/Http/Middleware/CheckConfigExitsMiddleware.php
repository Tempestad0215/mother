<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckConfigExitsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //        Verificar si existe configuracion registrada
        $config = Setting::first();

//        Si la configuracion no existe y no estamos en la ruta de configuracion por favor, redirige a la ventana
        if(!$config && !Route::is('setting.index') && $request->isMethod('get') ){
            return redirect()->route('setting.index');
        }

        return $next($request);
    }
}
