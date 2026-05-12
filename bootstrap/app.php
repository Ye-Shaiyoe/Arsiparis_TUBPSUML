
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.role.check' => \App\Http\Middleware\RedirectIfAdminRoleNotSelected::class,
            'it_support' => \App\Http\Middleware\EnsureIsITSupport::class,
        ]);
        
        $middleware->trustProxies(at: '*');

        $middleware->redirectTo(
            users: function ($request) {
                $user = auth()->user();
                if ($user) {
                    if ($user->isITSupport()) {
                        return route('itsupport.dashboard');
                    }
                    if ($user->isAdmin()) {
                        return route('admin.dashboard');
                    }
                }
                return route('dashboard');
            }
        );

        // Add Security headers middleware to web group
        $middleware->web(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();