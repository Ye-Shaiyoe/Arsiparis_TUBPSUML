<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddHstsHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add HSTS header on HTTPS connections
        if ($request->secure()) {
            // max-age=31536000 means 1 year (in seconds)
            // includeSubDomains applies the rule to all subdomains
            // preload allows the site to be included in browser HSTS preload lists
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
