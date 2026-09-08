<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman admin.');
        }

        $user = Auth::user();
        if ($user->is_admin || in_array($user->role, ['admin', 'master_admin'])) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Anda bukan admin.');
    }
}