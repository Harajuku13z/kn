<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleAjaxRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);
        
        // Si disable_ajax est présent dans la requête, ne pas traiter comme AJAX
        if ($request->has('disable_ajax')) {
            return $response;
        }
        
        // If this is an AJAX request and we're returning a view
        if ($request->ajax() && $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Only apply to successful responses
            if ($response instanceof Response && $response->isSuccessful()) {
                // Only apply to HTML responses (views)
                if (is_string($response->getContent()) && 
                    strpos($response->header('Content-Type', ''), 'text/html') !== false) {
                    
                    // Don't modify responses that already have the ajax-dashboard-content ID
                    if (strpos($response->getContent(), 'id="ajax-dashboard-content"') !== false) {
                        return $response;
                    }
                    
                    // Add the wrapper div to the response content
                    $content = '
                    <div id="ajax-dashboard-content">
                        ' . $response->getContent() . '
                    </div>';
                    $response->setContent($content);
                }
            }
        }
        
        return $response;
    }
} 