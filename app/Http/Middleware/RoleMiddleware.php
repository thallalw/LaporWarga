<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        // Jika middleware meminta 'admin', maka rt dan rw diizinkan
        if (in_array('admin', $roles)) {
            if (in_array($userRole, ['rt', 'rw'])) {
                return $next($request);
            }
        }

        // Cek spesifik role (hanya warga, atau hanya rt)
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
    }
}
