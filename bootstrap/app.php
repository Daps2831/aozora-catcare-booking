<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\Authenticate;


return Application::configure(basePath: dirname(path: __DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class, // Ini akan merujuk ke Illuminate\Auth\Middleware\Authenticate
            'admin' => \App\Http\Middleware\AdminMiddleware::class, // Ini merujuk ke middleware kustom Anda
        ]);

        // Jika Anda ingin mengarahkan pengguna yang belum login ke halaman login.form
       $middleware->redirectGuestsTo(fn () => route('login.form')); // <-- Hapus (Request $request)

        // Jika Anda ingin mengarahkan pengguna yang sudah login (tapi mencoba akses halaman login/register)
        // ke dashboard mereka
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->user()->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('user.dashboard');
        });

        
})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
