<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias middleware admin
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'admin.role' => \App\Http\Middleware\AdminRoleMiddleware::class,
        ]);

        // Exclude Midtrans notification from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle rate limiting pada login - redirect kembali dengan pesan error
        $exceptions->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->is('admin/login')) {
                return redirect()->route('admin.login')
                    ->withErrors(['throttle' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.'])
                    ->withInput($request->only('email'));
            }
        });
    })->create();
