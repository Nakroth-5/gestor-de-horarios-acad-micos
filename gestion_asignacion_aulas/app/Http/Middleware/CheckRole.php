<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Si no se especifican roles, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar si el usuario tiene alguno de los roles permitidos
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Si el usuario NO tiene los roles requeridos, redirigir según su rol
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirigir al usuario según su rol
     */
    protected function redirectBasedOnRole($user): Response
    {
        // Si es docente, redirigir a su horario
        if ($user->hasRole('Docente')) {
            return redirect()->route('my-schedule.index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Si es administrador u otro rol, redirigir al dashboard
        if ($user->hasRole('Administrador')) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Por defecto, redirigir al dashboard
        return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}
