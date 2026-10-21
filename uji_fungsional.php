<?php
/*
|--------------------------------------------------------------------------
| Uji Fungsional Alur Tulis (POST)
|--------------------------------------------------------------------------
| Menguji route POST yang mengandung logika bisnis inti: pembuatan menu,
| pengaturan stok, pengajuan request stok, dan alur pemesanan.
|
| Secara bawaan pengujian memakai basis data SQLite terpisah agar data
| asli pada MySQL tidak tersentuh. Untuk menguji langsung terhadap MySQL,
| setel penanda lingkungan UJI_PAKAI_MYSQL=1. Seluruh data uji akan
| dibersihkan kembali pada akhir pengujian.
|
| Jalankan: php uji_fungsional.php
*/

if (! getenv('UJI_PAKAI_MYSQL')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE='.__DIR__.'/database/uji_fungsional.sqlite');
}

putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

// Nonaktifkan pemeriksaan CSRF hanya selama pengujian ini.
// Pada aplikasi nyata CSRF tetap aktif; penanda ini dibaca oleh middleware.
putenv('UJI_CSRF_NONAKTIF=1');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$lolos = 0;
$gagal = 0;

/**
 * Cetak hasil satu pemeriksaan.
 */
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

echo "== UJI FUNGSIONAL ALUR TULIS ==\n\n";

// ---------------------------------------------------------------
// 1. BUAT MENU BARU (logika owner.menu.simpan)
// ---------------------------------------------------------------
echo "[1] Pembuatan menu baru\n";
$jumlahSebelum = App\Models\Menu::query()->count();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$session = app('session.store');
$session->start();
$session->put('owner_login', true);
$session->put('owner_username', 'owner');

$req = Illuminate\Http\Request::create('/owner/menu/simpan', 'POST', [
    'nama' => 'Ayam Geprek Sambal Matah',
    'harga' => 18000,
]);
$req->setLaravelSession($session);
$res = $kernel->handle($req);

periksa('Menu baru tersimpan', App\Models\Menu::query()->count() === $jumlahSebelum + 1,
    'jumlah menu: '.App\Models\Menu::query()->count());
periksa('Dialihkan kembali ke daftar menu', $res->getStatusCode() === 302,
    'status: '.$res->getStatusCode());

$menuBaru = App\Models\Menu::query()->where('nama', 'Ayam Geprek Sambal Matah')->first();
periksa('Data menu baru benar', $menuBaru !== null && (int) $menuBaru->harga === 18000,
    $menuBaru ? 'harga: '.$menuBaru->harga : 'menu tidak ditemukan');

// ---------------------------------------------------------------
// 2. VALIDASI MENU (nama kosong harus ditolak)
// ---------------------------------------------------------------
echo "\n[2] Validasi input menu\n";
$jumlahSebelum = App\Models\Menu::query()->count();
$req = Illuminate\Http\Request::create('/owner/menu/simpan', 'POST', ['nama' => '', 'harga' => 0]);
$req->setLaravelSession($session);
try {
    $kernel->handle($req);
    periksa('Menu tanpa nama ditolak', App\Models\Menu::query()->count() === $jumlahSebelum,
        'jumlah menu bertambah, seharusnya ditolak');
} catch (\Illuminate\Validation\ValidationException $e) {
    periksa('Menu tanpa nama ditolak', true);
}

// ---------------------------------------------------------------
// 3. HAPUS MENU
// ---------------------------------------------------------------
echo "\n[3] Penghapusan menu\n";
if ($menuBaru) {
    $req = Illuminate\Http\Request::create('/owner/menu/hapus/'.$menuBaru->id, 'POST');
    $req->setLaravelSession($session);
    $kernel->handle($req);
    periksa('Menu terhapus dari basis data',
        App\Models\Menu::query()->find($menuBaru->id) === null);
}

// ---------------------------------------------------------------
// 4. MODEL STOK OUTLET (relasi dan pembatasan unik)
// ---------------------------------------------------------------
echo "\n[4] Model stok outlet\n";
$adminOutlet = App\Models\AdminOutlet::query()->first();
periksa('Akun outlet tersedia', $adminOutlet !== null);
if ($adminOutlet) {
    periksa('Relasi lokasi outlet berjalan',
        $adminOutlet->lokasi() !== null);
}

$stok = App\Models\StokOutlet::query()->first();
periksa('Data stok outlet tersedia', $stok !== null);

// ---------------------------------------------------------------
// 5. REQUEST STOK OLEH OUTLET
// ---------------------------------------------------------------
echo "\n[5] Pengajuan request stok\n";
$jumlahSebelum = App\Models\RequestStok::query()->count();

$sessionOutlet = app('session.store');
$sessionOutlet->start();
auth()->guard('outlet')->setUser($adminOutlet);

$req = Illuminate\Http\Request::create('/outlet/request-stok', 'POST', [
    'nama_barang' => 'Ayam Potong',
    'jumlah' => 5,
    'satuan' => 'kg',
]);
$req->setLaravelSession($sessionOutlet);
try {
    $res = $kernel->handle($req);
    $jumlahSekarang = App\Models\RequestStok::query()->count();
    periksa('Request stok tercatat', $jumlahSekarang === $jumlahSebelum + 1,
        'jumlah: '.$jumlahSekarang.' | status: '.$res->getStatusCode());

    $reqTerakhir = App\Models\RequestStok::query()->latest('id')->first();
    periksa('Data request sesuai masukan',
        $reqTerakhir !== null
        && $reqTerakhir->nama_barang === 'Ayam Potong'
        && (int) $reqTerakhir->jumlah === 5
        && $reqTerakhir->status === 'Pending',
        $reqTerakhir ? 'barang: '.$reqTerakhir->nama_barang.' | status: '.$reqTerakhir->status : 'kosong');
} catch (\Throwable $e) {
    periksa('Request stok tercatat', false, get_class($e).': '.$e->getMessage());
}

// ---------------------------------------------------------------
// 6. PESANAN PUBLIK (checkout)
// ---------------------------------------------------------------
echo "\n[6] Alur pemesanan publik\n";
$menu = App\Models\Menu::query()->first();
$lokasiUji = App\Models\Lokasi::query()->whereNotNull('username_admin')->first();
$jumlahPesananSebelum = App\Models\TransaksiPesanan::query()->count();

$req = Illuminate\Http\Request::create('/pesanan/proses-checkout', 'POST', [
    'cust_name' => 'Pelanggan Uji',
    'cust_phone' => '628123456789',
    'payment' => 'Cash',
    'total_final' => 16000,
    'cart_data' => json_encode([
        ['nama' => $menu->nama, 'harga' => (int) $menu->harga, 'qty' => 2, 'catatan' => ''],
    ]),
    'outlet_name' => $lokasiUji->nama,
]);
$req->setLaravelSession(app('session.store'));
try {
    $res = $kernel->handle($req);
    periksa('Checkout dialihkan ke halaman pelacakan', $res->getStatusCode() === 302,
        'status: '.$res->getStatusCode());

    $jumlahPesananSesudah = App\Models\TransaksiPesanan::query()->count();
    periksa('Pesanan baru tercatat', $jumlahPesananSesudah === $jumlahPesananSebelum + 1,
        'jumlah: '.$jumlahPesananSesudah);
} catch (\Throwable $e) {
    periksa('Checkout diproses', false, get_class($e).': '.$e->getMessage());
}

// ---------------------------------------------------------------
// 7. PEMBERSIHAN DATA UJI
// ---------------------------------------------------------------
echo "\n[7] Pembersihan data uji\n";

// Hapus permintaan stok uji.
$hapusRequest = App\Models\RequestStok::query()
    ->where('nama_barang', 'Ayam Potong')
    ->where('jumlah', 5)
    ->delete();
periksa('Request stok uji dibersihkan', $hapusRequest > 0, "$hapusRequest baris");

// Hapus log aktivitas yang menyertai permintaan stok uji.
$hapusLog = App\Models\LogAktivitas::query()
    ->where('aksi', 'Request Stok')
    ->where('detail', 'like', '%Ayam Potong%')
    ->delete();
periksa('Log aktivitas uji dibersihkan', $hapusLog > 0, "$hapusLog baris");

// Hapus pesanan hasil uji checkout.
$hapusPesanan = App\Models\TransaksiPesanan::query()
    ->where('nama_pemesan', 'Pelanggan Uji')
    ->delete();
periksa('Pesanan uji dibersihkan', $hapusPesanan > 0, "$hapusPesanan baris");

// ---------------------------------------------------------------
// RINGKASAN
// ---------------------------------------------------------------
echo "\n".str_repeat('=', 55)."\n";
echo "Lolos: $lolos | Bermasalah: $gagal\n";
