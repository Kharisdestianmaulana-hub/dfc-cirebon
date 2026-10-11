<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware jaga-mitra.
 * Memastikan hanya mitra yang sudah login dapat membuka portal stok mitra.
 */
class JagaMitra
{
    /**
     * Menangani permintaan masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('mitra')->check()) {
            return redirect()->route('mitra.login');
        }

        return $next($request);
    }
}
