<?php
/*
|--------------------------------------------------------------------------
| Uji Asap Terautentikasi
|--------------------------------------------------------------------------
| Skrip ini masuk sebagai setiap peran (gudang, outlet, mitra) lalu
| memanggil halaman dasbor masing-masing untuk memastikan guard,
| model, dan blade saling terhubung dengan benar.
|
| Jalankan: php uji_asap_auth.php
*/

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE='.__DIR__.'/database/uji_asap.sqlite');
putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$lolos = 0;
$gagal = 0;

/**
 * Panggil satu URI sebagai pengguna tertentu.
 *
 * @param  array<string, mixed>  $sesi  Data sesi tambahan (misalnya penanda owner).
 */
function panggil($kernel, string $metode, string $uri, ?string $guard, $pengguna, &$lolos, &$gagal, array $sesi = [])
{
    // Ajarkan guard memakai pengguna uji tanpa perlu mencocokkan kata sandi.
    if ($guard !== null && $pengguna !== null) {
        auth()->guard($guard)->setUser($pengguna);
    }

    // Bangun sesi yang sudah berisi data login, lalu tempelkan ke permintaan.
    $store = app('session.store');
    $store->start();
    foreach ($sesi as $kunci => $nilai) {
        $store->put($kunci, $nilai);
    }

    $request = Illuminate\Http\Request::create($uri, $metode);
    $request->setLaravelSession($store);

    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $tanda = $status === 200 ? 'OK  ' : 'WARN';
        if ($status === 200) { $lolos++; } else { $gagal++; }
        echo "$tanda [$status] ".str_pad($guard ?? 'publik', 8)." $metode $uri\n";
        if ($status === 302) {
            echo '     -> dialihkan ke '.$response->headers->get('Location')."\n";
        }
    } catch (\Throwable $e) {
        $gagal++;
        echo "FAIL ".str_pad($guard ?? 'publik', 8)." $metode $uri -> ".$e->getMessage()."\n";
        echo '     '.$e->getFile().':'.$e->getLine()."\n";
    }
}

// ---------------------------------------------------------------
// Siapkan data pengguna uji
// ---------------------------------------------------------------
$adminGudang = App\Models\AdminGudang::first();
$adminOutlet = App\Models\AdminOutlet::first();
$mitra       = App\Models\AkunMitra::first();

echo "== Wilayah Gudang ==\n";
panggil($kernel, 'GET', '/gudang', 'gudang', $adminGudang, $lolos, $gagal);
panggil($kernel, 'GET', '/gudang/riwayat', 'gudang', $adminGudang, $lolos, $gagal);

echo "\n== Wilayah Outlet ==\n";
panggil($kernel, 'GET', '/outlet', 'outlet', $adminOutlet, $lolos, $gagal);
panggil($kernel, 'GET', '/outlet/pesanan-masuk', 'outlet', $adminOutlet, $lolos, $gagal);
panggil($kernel, 'GET', '/outlet/laporan', 'outlet', $adminOutlet, $lolos, $gagal);
panggil($kernel, 'GET', '/outlet/riwayat-request', 'outlet', $adminOutlet, $lolos, $gagal);

echo "\n== Wilayah Mitra ==\n";
panggil($kernel, 'GET', '/mitra', 'mitra', $mitra, $lolos, $gagal);

echo "\n== Wilayah Owner ==\n";
// Owner memakai penanda sesi, bukan guard tersendiri.
panggil($kernel, 'GET', '/owner', null, null, $lolos, $gagal, ['owner_login' => true, 'owner_username' => 'owner']);

echo "\nLolos: $lolos | Bermasalah: $gagal\n";
