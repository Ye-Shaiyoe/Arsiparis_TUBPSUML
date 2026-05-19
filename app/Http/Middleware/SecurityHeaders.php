<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');

        // HSTS (Only on HTTPS)
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Content Security Policy (CSP)
        // Note: This is a balanced CSP that allows common CDNs used in the project
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://cdn.tailwindcss.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https: http: blob:; " .
               "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "frame-src 'self' https://www.google.com; " .
               "connect-src 'self' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net;";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
