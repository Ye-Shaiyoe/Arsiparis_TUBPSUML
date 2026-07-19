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
        // Halaman admin masih memakai Tailwind CDN yang butuh unsafe-eval untuk parse config JS.
        // Halaman auth & user memakai CSP lebih ketat tanpa unsafe-eval karena tidak pakai CDN Tailwind.
        $isAdminOrSupport = $request->is('Admin/*') || $request->is('IT-Support/*') || $request->is('become-it-support');

        $scriptSrc = $isAdminOrSupport
            // Admin & IT Support: perlu unsafe-eval untuk cdn.tailwindcss.com
            ? "'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://cdn.tailwindcss.com"
            // Auth & User pages: tanpa unsafe-eval, tanpa CDN Tailwind — CSP lebih ketat
            : "'self' 'unsafe-inline' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com";

        $csp = "default-src 'self'; " .
               "script-src {$scriptSrc}; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "frame-src 'self' https://www.google.com; " .
               "connect-src 'self' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
