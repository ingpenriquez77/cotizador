<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Maneja las peticiones entrantes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Usa el método isAdmin() que ya tienes en tu modelo User
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Si es viewer o cualquier otro rol, lo redirige al Dashboard
        return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
