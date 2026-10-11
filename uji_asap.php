<?php
// Uji asap: pastikan setiap route dapat dirender tanpa error.
// Jalankan: php uji_asap.php
// Gunakan basis data SQLite uji agar tidak menyentuh MySQL produksi.

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE='.__DIR__.'/database/uji_asap.sqlite');
putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urut = [
    '/', '/menu', '/lokasi', '/profil', '/kemitraan', '/pesanan',
    '/tracking/riwayat',
    '/gudang/login', '/outlet/login', '/mitra/login', '/owner/login',
];

$lolos = 0; $gagal = 0; $rincian = [];
foreach ($urut as $uri) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "OK   [$status] $uri\n"; $lolos++;
        } else {
            echo "WARN [$status] $uri\n"; $gagal++;
            $rincian[$uri] = "HTTP $status";
        }
    } catch (\Throwable $e) {
        echo "FAIL $uri -> ".get_class($e).': '.$e->getMessage()."\n";
        echo '     di '.$e->getFile().':'.$e->getLine()."\n";
        $gagal++;
        $rincian[$uri] = $e->getMessage();
    }
}
echo "\nLolos: $lolos | Bermasalah: $gagal\n";

// Tampilkan pesan error dari log bila ada
$log = __DIR__.'/storage/logs/laravel.log';
if ($gagal > 0 && is_file($log)) {
    echo "\n---- 60 baris terakhir log ----\n";
    $baris = file($log);
    echo implode('', array_slice($baris, -60));
}
