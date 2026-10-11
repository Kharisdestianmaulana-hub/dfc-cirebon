<?php
/*
|--------------------------------------------------------------------------
| Uji Paritas seluruh route GET terhadap MySQL
|--------------------------------------------------------------------------
| Versi pembaruan dari uji_paritas.php yang kini memakai basis data MySQL
| yang sudah terisi data seeder. Memastikan seluruh halaman pada kelima
| wilayah akses benar-benar dapat dirender.
|
| Jalankan: php uji_paritas.php
*/

putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// ---------------------------------------------------------------
// Siapkan data pengguna uji langsung dari MySQL
// ---------------------------------------------------------------
$adminGudang = App\Models\AdminGudang::query()->first();
$adminOutlet = App\Models\AdminOutlet::query()->first();
$mitra       = App\Models\AkunMitra::query()->first();

$lolos = 0;
$gagal = 0;
$catatan = [];

/**
 * Panggil satu URI sebagai pengguna tertentu lalu catat hasilnya.
 */
function panggil($kernel, string $uri, ?string $guard, $pengguna, array $sesi = []): array
{
    if ($guard !== null && $pengguna !== null) {
        auth()->guard($guard)->setUser($pengguna);
    }

    $store = app('session.store');
    $store->start();
    foreach ($sesi as $kunci => $nilai) {
        $store->put($kunci, $nilai);
    }

    $request = Illuminate\Http\Request::create($uri, 'GET');
    $request->setLaravelSession($store);

    try {
        $response = $kernel->handle($request);

        return ['status' => $response->getStatusCode(), 'pesan' => null];
    } catch (\Throwable $e) {
        return ['status' => 0, 'pesan' => get_class($e).': '.$e->getMessage()];
    }
}

/**
 * Uji sekelompok route.
 */
function ujiKelompok($kernel, string $nama, array $daftar, ?string $guard, $pengguna, array $sesi, &$lolos, &$gagal, &$catatan)
{
    echo "== $nama ==\n";
    foreach ($daftar as $uri) {
        $hasil = panggil($kernel, $uri, $guard, $pengguna, $sesi);
        if ($hasil['status'] === 200) {
            $lolos++;
            echo "  OK    $uri\n";
        } else {
            $gagal++;
            $catatan[$uri] = $hasil['pesan'] ?? ('HTTP '.$hasil['status']);
            echo "  GAGAL [{$hasil['status']}] $uri\n";
        }
    }
    echo "\n";
}

ujiKelompok($kernel, 'PUBLIK', [
    '/', '/menu', '/lokasi', '/profil', '/kemitraan', '/pesanan',
    '/tracking/riwayat',
], null, null, [], $lolos, $gagal, $catatan);

ujiKelompok($kernel, 'HALAMAN LOGIN', [
    '/gudang/login', '/outlet/login', '/mitra/login', '/owner/login',
], null, null, [], $lolos, $gagal, $catatan);

ujiKelompok($kernel, 'PANEL GUDANG', [
    '/gudang', '/gudang/riwayat',
], 'gudang', $adminGudang, [], $lolos, $gagal, $catatan);

ujiKelompok($kernel, 'PANEL OUTLET', [
    '/outlet', '/outlet/pesanan-masuk', '/outlet/laporan', '/outlet/riwayat-request',
], 'outlet', $adminOutlet, [], $lolos, $gagal, $catatan);

ujiKelompok($kernel, 'PORTAL MITRA', [
    '/mitra',
], 'mitra', $mitra, [], $lolos, $gagal, $catatan);

$sesiOwner = ['owner_login' => true, 'owner_username' => 'owner'];
ujiKelompok($kernel, 'PANEL OWNER', [
    '/owner/dashboard', '/owner/menu', '/owner/lokasi', '/owner/outlet-karyawan',
    '/owner/stok-gudang', '/owner/request-stok', '/owner/atur-gudang',
    '/owner/laporan-full', '/owner/riwayat-penjualan', '/owner/log-aktivitas',
    '/owner/profil', '/owner/kemitraan', '/owner/akun-mitra',
], null, null, $sesiOwner, $lolos, $gagal, $catatan);

ujiKelompok($kernel, 'FORM OWNER', [
    '/owner/menu/form', '/owner/lokasi/form',
], null, null, $sesiOwner, $lolos, $gagal, $catatan);

echo str_repeat('=', 60)."\n";
echo "RINGKASAN UJI PARITAS (MySQL)\n";
echo str_repeat('=', 60)."\n";
echo "Lolos      : $lolos\n";
echo "Bermasalah : $gagal\n";

if ($catatan) {
    echo "\nDaftar masalah:\n";
    foreach ($catatan as $uri => $pesan) {
        echo "  - $uri : $pesan\n";
    }
}
