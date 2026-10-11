<?php
/*
|--------------------------------------------------------------------------
| Uji Fungsional Fitur Chat Pesanan
|--------------------------------------------------------------------------
| Menguji percakapan antara konsumen dan outlet pada sebuah pesanan:
| konsumen mengirim pesan, outlet membalas, dan kedua halaman menampilkan
| percakapan tersebut.
|
| Pengujian berjalan langsung terhadap basis data MySQL (data asli).
| Seluruh pesan uji dibersihkan kembali pada akhir pengujian.
|
| Jalankan: php uji_chat.php
*/

putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

// Nonaktifkan pemeriksaan CSRF hanya selama pengujian ini.
putenv('UJI_CSRF_NONAKTIF=1');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AdminOutlet;
use App\Models\ChatPesanan;
use App\Models\TransaksiPesanan;

$lolos = 0;
$gagal = 0;

/** Cetak hasil satu pemeriksaan. */
function periksa(string $nama, bool $kondisi, string $detail = '')
{
    global $lolos, $gagal;
    if ($kondisi) {
        $lolos++;
        echo "  OK    $nama\n";
    } else {
        $gagal++;
        echo "  GAGAL $nama".($detail !== '' ? " -> $detail" : '')."\n";
    }
}

echo "== UJI FUNGSIONAL FITUR CHAT PESANAN ==\n\n";

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Pilih satu pesanan nyata milik outlet sutawinangun yang sudah punya percakapan.
$pesanan = TransaksiPesanan::query()
    ->where('kode_outlet', 'like', '%sutawinangun%')
    ->whereIn('order_id', ChatPesanan::query()->select('order_id'))
    ->first();

if (! $pesanan) {
    echo "Tidak ada pesanan dengan percakapan. Pengujian dihentikan.\n";
    exit(1);
}

$orderId = $pesanan->order_id;
echo "Pesanan uji: $orderId\n\n";

$jumlahAwal = ChatPesanan::query()->where('order_id', $orderId)->count();
echo "[0] Jumlah pesan awal: $jumlahAwal\n\n";

// ---------------------------------------------------------------
// 1. KONSUMEN MENGIRIM PESAN
// ---------------------------------------------------------------
echo "[1] Konsumen mengirim pesan\n";

$teksKonsumen = 'Uji otomatis: apakah pesanan saya sudah siap?';

$store = app('session.store');
$store->start();

$req = Illuminate\Http\Request::create('/tracking/chat', 'POST', [
    'order_id' => $orderId,
    'pesan' => $teksKonsumen,
]);
$req->setLaravelSession($store);
$res = $kernel->handle($req);

periksa('Route chat konsumen mengembalikan pengalihan', in_array($res->getStatusCode(), [301, 302, 303], true), 'status '.$res->getStatusCode());

$pesanKonsumen = ChatPesanan::query()
    ->where('order_id', $orderId)
    ->where('pesan', $teksKonsumen)
    ->first();

periksa('Pesan konsumen tersimpan', $pesanKonsumen !== null);
periksa('Pengirim ditandai konsumen', $pesanKonsumen && $pesanKonsumen->pengirim === 'konsumen');
periksa('Pesan baru belum dibaca', $pesanKonsumen && $pesanKonsumen->is_read === false);

// ---------------------------------------------------------------
// 2. VALIDASI PESAN KOSONG DITOLAK
// ---------------------------------------------------------------
echo "\n[2] Validasi pesan kosong\n";

$jumlahSebelumKosong = ChatPesanan::query()->where('order_id', $orderId)->count();

$store2 = app('session.store');
$store2->start();

$reqKosong = Illuminate\Http\Request::create('/tracking/chat', 'POST', [
    'order_id' => $orderId,
    'pesan' => '',
]);
$reqKosong->setLaravelSession($store2);

// Laravel mengubah kegagalan validasi menjadi pengalihan kembali,
// jadi yang diperiksa adalah statusnya serta tidak adanya baris baru.
$statusKosong = 0;
try {
    $statusKosong = $kernel->handle($reqKosong)->getStatusCode();
} catch (\Illuminate\Validation\ValidationException $e) {
    $statusKosong = 422;
}

$jumlahSesudahKosong = ChatPesanan::query()->where('order_id', $orderId)->count();

periksa('Permintaan kosong dialihkan atau ditolak', in_array($statusKosong, [302, 303, 422], true), 'status '.$statusKosong);
periksa('Pesan kosong tidak tersimpan', $jumlahSesudahKosong === $jumlahSebelumKosong);

// ---------------------------------------------------------------
// 3. OUTLET MEMBALAS PESAN
// ---------------------------------------------------------------
echo "\n[3] Outlet membalas pesan\n";

$adminOutlet = AdminOutlet::query()->where('username', 'sutawinangun')->first();
periksa('Akun outlet sutawinangun tersedia', $adminOutlet !== null);

if ($adminOutlet) {
    auth()->guard('outlet')->setUser($adminOutlet);

    $store3 = app('session.store');
    $store3->start();

    $teksOutlet = 'Uji otomatis: pesanan Anda sedang kami siapkan.';

    $reqOutlet = Illuminate\Http\Request::create('/outlet/pesanan-masuk/chat', 'POST', [
        'order_id' => $orderId,
        'pesan' => $teksOutlet,
    ]);
    $reqOutlet->setLaravelSession($store3);
    $resOutlet = $kernel->handle($reqOutlet);

    periksa('Route chat outlet mengembalikan pengalihan', in_array($resOutlet->getStatusCode(), [301, 302, 303], true), 'status '.$resOutlet->getStatusCode());

    $pesanOutlet = ChatPesanan::query()
        ->where('order_id', $orderId)
        ->where('pesan', $teksOutlet)
        ->first();

    periksa('Balasan outlet tersimpan', $pesanOutlet !== null);
    periksa('Pengirim ditandai outlet', $pesanOutlet && $pesanOutlet->pengirim === 'outlet');
}

// ---------------------------------------------------------------
// 4. OUTLET TIDAK BOLEH MEMBALAS PESANAN OUTLET LAIN
// ---------------------------------------------------------------
echo "\n[4] Pembatasan akses antar outlet\n";

// Seluruh pesanan asli ditujukan ke sutawinangun, jadi dibuat satu pesanan
// sementara milik outlet cipto untuk menguji pembatasan akses.
$orderLain = 'UJI-CHAT-LINTAS';
TransaksiPesanan::query()->where('order_id', $orderLain)->delete();
TransaksiPesanan::query()->create([
    'order_id' => $orderLain,
    'nama_pemesan' => 'Uji Otomatis',
    'no_hp' => '0800000000',
    'kode_outlet' => 'cipto',
    'total_bayar' => 0,
    'metode_bayar' => 'Cash',
    'detail_pesanan' => '[]',
    'status_pesanan' => 'Menunggu Konfirmasi',
    'waktu_pesan' => now(),
]);

if ($adminOutlet) {
    auth()->guard('outlet')->setUser($adminOutlet);

    $store4 = app('session.store');
    $store4->start();

    $reqLain = Illuminate\Http\Request::create('/outlet/pesanan-masuk/chat', 'POST', [
        'order_id' => $orderLain,
        'pesan' => 'Uji otomatis: pesan lintas outlet.',
    ]);
    $reqLain->setLaravelSession($store4);

    $kode = 0;
    try {
        $kode = $kernel->handle($reqLain)->getStatusCode();
    } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
        $kode = 404;
    }

    periksa('Outlet lain ditolak (404)', $kode === 404, 'status '.$kode);

    $lintas = ChatPesanan::query()
        ->where('pesan', 'Uji otomatis: pesan lintas outlet.')
        ->exists();

    periksa('Pesan lintas outlet tidak tersimpan', ! $lintas);
} else {
    echo "  (dilewati: akun outlet tidak tersedia)\n";
}

// ---------------------------------------------------------------
// 5. HALAMAN MENAMPILKAN PERCAKAPAN
// ---------------------------------------------------------------
echo "\n[5] Halaman menampilkan percakapan\n";

$halamanKonsumen = file_get_contents(
    'http://127.0.0.1:8000/tracking/detail?id='.urlencode($orderId)
);

periksa('Halaman konsumen memuat panel chat', str_contains($halamanKonsumen, 'Chat dengan Outlet'));
periksa('Halaman konsumen menampilkan pesan konsumen', str_contains($halamanKonsumen, 'apakah pesanan saya sudah siap'));

$totalAkhir = ChatPesanan::query()->where('order_id', $orderId)->count();
echo "\n  Jumlah pesan akhir: $totalAkhir\n";

// ---------------------------------------------------------------
// 6. PEMBERSIHAN DATA UJI
// ---------------------------------------------------------------
echo "\n[6] Pembersihan data uji\n";

$dihapus = ChatPesanan::query()
    ->where('pesan', 'like', 'Uji otomatis:%')
    ->delete();

periksa('Pesan uji dibersihkan', $dihapus > 0, "$dihapus baris");

// Hapus pesanan sementara milik outlet lain.
$hapusPesanan = TransaksiPesanan::query()->where('order_id', $orderLain)->delete();
periksa('Pesanan sementara dibersihkan', $hapusPesanan === 1, "$hapusPesanan baris");

$sisa = ChatPesanan::query()->where('order_id', $orderId)->count();
periksa('Jumlah pesan kembali seperti semula', $sisa === $jumlahAwal, "akhir $sisa, awal $jumlahAwal");

echo "\n=======================================================\n";
echo "Lolos: $lolos | Bermasalah: $gagal\n";
echo "=======================================================\n";

exit($gagal > 0 ? 1 : 0);
