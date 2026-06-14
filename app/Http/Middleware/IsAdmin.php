<?php

namespace App\Http\Middleware;   // ← ini yang benar

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
    }
}