<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si el usuario SOLO tiene el rol de Docente (no es admin ni otro rol)
            if ($user->hasRole('Docente') && !$user->hasRole('Administrador')) {
                // Si está intentando acceder al dashboard o rutas administrativas
                $restrictedRoutes = [
                    'dashboard',
                    'user.*',
                    'role.*',
                    'subject.*',
                    'teacher-subject.*',
                    'group.*',
                    'classroom.*',
                    'infrastructure.*',
                    'schedule-block.*',
                    'manual-schedule-assignment.*',
                    // 'attendance.*', // PERMITIDO para docentes
                    // 'special-reservations.*', // PERMITIDO para docentes
                    'academic-periods.*',
                    'university-careers.*',
                    'auditLog.*',
                    'user-import.*',
                ];

                foreach ($restrictedRoutes as $routePattern) {
                    if ($request->routeIs($routePattern)) {
                        return redirect()->route('my-schedule.index')->with('info', 'Como docente, esta es tu vista personalizada.');
                    }
                }
            }
        }

        return $next($request);
    }
}
