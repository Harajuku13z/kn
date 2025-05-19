<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToDashboard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est connecté et est un administrateur, le rediriger vers le dashboard admin
        if (Auth::check() && Auth::user()->role === 'admin' && $request->routeIs('dashboard')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
} 