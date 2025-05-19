<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionTable
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check the current session driver
        if (Config::get('session.driver') === 'database') {
            // Check if the sessions table exists, if not, switch to file driver
            if (!Schema::hasTable('sessions')) {
                Config::set('session.driver', 'file');
            }
        }

        return $next($request);
    }
} 