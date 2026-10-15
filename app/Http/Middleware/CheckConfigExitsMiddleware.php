<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CheckConfigExitsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si existe configuracion registrada
        $config = Setting::getGlobal();

        if(!$config){
            if(!Route::is('setting.*') && !Route::is('login')){
                if ($request->isMethod('get')){
                    return redirect(route('setting.index'));
                }
            }
        }


        return $next($request);
    }
}
