<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CmsAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('cms.login')->with('error', 'Silakan login terlebih dahulu untuk mengakses CMS.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('cms.login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Super Admin.');
        }

        return $next($request);
    }
}
