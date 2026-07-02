<?php

namespace App\Http\Middleware;

use App\Models\CashRegister;
use Closure;
use Illuminate\Http\Request;

class EnsureCashRegisterIsOpenMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $activeCashRegister = CashRegister::getActiveForUser(auth()->user()->uuid);

        if(!$activeCashRegister && $request->routeIs('sale.*'))
        {
            // ...lo rebotamos al dashboard o a una vista segura con un mensaje de alerta
            return redirect()->route('dashboard')
                ->with('error', 'Debes abrir una caja antes de poder interactuar con el módulo de ventas.');
        }

        return $next($request);
    }
}
