<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                // Update last login
                $request->user()->update(['last_login_at' => now()]);
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki hak untuk halaman ini.');
    }
}