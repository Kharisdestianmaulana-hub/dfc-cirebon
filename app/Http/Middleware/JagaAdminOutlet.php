<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware jaga-admin-outlet.
 * Memastikan hanya karyawan outlet yang sudah login dapat membuka panel outlet.
 * Bila belum login, pengguna diarahkan ke halaman login outlet.
 */
class JagaAdminOutlet
{
    /**
     * Menangani permintaan masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('outlet')->check()) {
            return redirect()->route('outlet.login');
        }

        return $next($request);
    }
}
