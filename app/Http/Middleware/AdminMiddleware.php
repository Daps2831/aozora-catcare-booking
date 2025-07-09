<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna yang login adalah admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);  // Lanjutkan jika admin
        }

        // Jika bukan admin, redirect ke dashboard user
        return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki akses sebagai admin.');
    }
}

