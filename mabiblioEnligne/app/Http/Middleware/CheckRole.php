<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // Vérifier si l'utilisateur a un des rôles autorisés
        foreach ($roles as $role) {
            if (Auth::user()->hasRole($role)) {
                return $next($request);
            }
        }
        
        // Si l'utilisateur n'a pas les droits, rediriger vers la page d'accueil
        return redirect()->route('front.index')
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
