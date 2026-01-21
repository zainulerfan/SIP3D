<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Belum login
        if (!auth()->check()) {
            abort(401);
        }

        // 🔥 SATU-SATUNYA SUMBER KEBENARAN
        $userRole = auth()->user()->role;

        \Illuminate\Support\Facades\Log::info('RoleMiddleware Check', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => $userRole,
            'required_role' => $role,
            'url' => $request->url(),
        ]);

        // Jika role tidak sesuai → tolak
        if ($userRole !== $role) {
            \Illuminate\Support\Facades\Log::warning('RoleMiddleware: Access Denied', [
                'user_role' => $userRole,
                'required' => $role
            ]);
            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
