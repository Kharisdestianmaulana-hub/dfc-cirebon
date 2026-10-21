<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware jaga-owner.
 * Memastikan hanya owner yang sudah login dapat membuka panel owner.
 * Login owner memakai kredensial khusus yang divalidasi lapisan layanan.
 */
class JagaOwner
{
    /**
     * Menangani permintaan masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('owner_login', false)) {
            return redirect()->route('owner.login');
        }

        return $next($request);
    }
}
