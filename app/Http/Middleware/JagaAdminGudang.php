<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware jaga-admin-gudang.
 * Memastikan hanya admin gudang pusat yang sudah login dapat membuka panel gudang.
 */
class JagaAdminGudang
{
    /**
     * Menangani permintaan masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('gudang')->check()) {
            return redirect()->route('gudang.login');
        }

        return $next($request);
    }
}
