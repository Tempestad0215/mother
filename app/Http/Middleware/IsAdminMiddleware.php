<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y tiene el rol de administrador
        if (Auth::check()) {
            return $next($request);
        }

        // Retornar una respuesta prohibida (403) con un mensaje
        abort(403, 'Acceso denegado. Solo los administradores pueden acceder a esta página.');
    }
}
