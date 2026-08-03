<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke fitur ini.'], 403);
            }
            return redirect()->route('cms.dashboard')->with('error', 'Akses ditolak. Fitur ini hanya untuk ' . ($role === 'super_admin' ? 'Super Admin' : $role) . '.');
        }

        return $next($request);
    }
}
