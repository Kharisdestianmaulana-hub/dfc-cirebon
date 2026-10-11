<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftar alias middleware kustom untuk penjagaan tiap area.
        $middleware->alias([
            'jaga.outlet' => \App\Http\Middleware\JagaAdminOutlet::class,
            'jaga.gudang' => \App\Http\Middleware\JagaAdminGudang::class,
            'jaga.mitra' => \App\Http\Middleware\JagaMitra::class,
            'jaga.owner' => \App\Http\Middleware\JagaOwner::class,
        ]);

        // Pengecualian CSRF hanya dipakai oleh skrip pengujian otomatis.
        // Pada penggunaan normal penanda ini tidak pernah diset sehingga
        // perlindungan CSRF tetap berjalan penuh.
        if (env('UJI_CSRF_NONAKTIF')) {
            $middleware->validateCsrfTokens(except: ['*']);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
