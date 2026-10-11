<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KemitraanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Daftar Rute Aplikasi DFC Cirebon
|--------------------------------------------------------------------------
|
| Seluruh rute dikelompokkan per area agar mudah ditelusuri:
| 1. Area Publik dan Konsumen
| 2. Area Gudang Pusat
| 3. Area Outlet (Karyawan)
| 4. Area Mitra
| 5. Area Owner
|
*/

// =====================================================================
// 1. AREA PUBLIK DAN KONSUMEN
// =====================================================================

// Halaman beranda.
Route::get('/', [HomeController::class, 'index'])->name('beranda');

// Halaman daftar menu publik.
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Halaman peta dan daftar lokasi.
Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');

// Halaman profil perusahaan.
Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

// Halaman dan proses pengajuan kemitraan.
Route::get('/kemitraan', [KemitraanController::class, 'index'])->name('kemitraan.index');
Route::post('/kemitraan/kirim', [KemitraanController::class, 'kirim'])->name('kemitraan.kirim');

// Alur pemesanan konsumen.
Route::prefix('pesanan')->name('pesanan.')->group(function () {
    // Halaman memilih menu untuk dipesan.
    Route::get('/', [PesananController::class, 'index'])->name('index');
    // Endpoint JSON status outlet untuk pembaruan berkala.
    Route::get('/status-outlet', [PesananController::class, 'statusOutlet'])->name('status_outlet');
    // Halaman konfirmasi pesanan.
    Route::post('/checkout', [PesananController::class, 'checkout'])->name('checkout');
    // Proses menyimpan pesanan ke basis data.
    Route::post('/proses-checkout', [PesananController::class, 'prosesCheckout'])->name('proses_checkout');
    // Halaman pembayaran QRIS.
    Route::get('/qris/{orderId}', [PesananController::class, 'qris'])->name('qris');
    // Halaman cetak struk pesanan.
    Route::get('/struk/{orderId}', [PesananController::class, 'struk'])->name('struk');
});

// Pelacakan dan riwayat pesanan konsumen.
Route::prefix('tracking')->name('tracking.')->group(function () {
    // Alihkan ke halaman riwayat sebagai halaman bawaan.
    Route::get('/', fn () => redirect()->route('tracking.riwayat'))->name('index');
    // Halaman pencarian riwayat pesanan.
    Route::match(['get', 'post'], '/riwayat', [TrackingController::class, 'riwayat'])->name('riwayat');
    // Halaman detail pelacakan pesanan.
    Route::get('/detail', [TrackingController::class, 'detail'])->name('detail');
    // Kirim pesan chat konsumen untuk sebuah pesanan.
    Route::post('/chat', [TrackingController::class, 'kirimChat'])->name('chat');
});

// =====================================================================
// 2. AREA GUDANG PUSAT
// =====================================================================
Route::prefix('gudang')->name('gudang.')->group(function () {
    // Halaman login admin gudang (dapat diakses tanpa login).
    Route::get('/login', [GudangController::class, 'tampilLogin'])->name('login');
    Route::post('/login', [GudangController::class, 'prosesLogin'])->name('login.proses');

    // Rute yang wajib login admin gudang.
    Route::middleware('jaga.gudang')->group(function () {
        // Daftar stok gudang pusat.
        Route::get('/', [GudangController::class, 'index'])->name('index');
        // Proses pembaruan stok.
        Route::post('/update', [GudangController::class, 'update'])->name('update');
        // Riwayat perubahan stok.
        Route::get('/riwayat', [GudangController::class, 'riwayat'])->name('riwayat');
        // Proses logout.
        Route::post('/logout', [GudangController::class, 'logout'])->name('logout');
    });
});

// =====================================================================
// 3. AREA OUTLET (KARYAWAN)
// =====================================================================
Route::prefix('outlet')->name('outlet.')->group(function () {
    // Halaman login karyawan outlet (dapat diakses tanpa login).
    Route::get('/login', [OutletController::class, 'tampilLogin'])->name('login');
    Route::post('/login', [OutletController::class, 'prosesLogin'])->name('login.proses');

    // Rute yang wajib login karyawan outlet.
    Route::middleware('jaga.outlet')->group(function () {
        // Panel stok dan penjualan outlet.
        Route::get('/', [OutletController::class, 'index'])->name('index');
        // Catat penjualan offline.
        Route::post('/penjualan', [OutletController::class, 'simpanPenjualan'])->name('penjualan');
        // Perbarui stok menu outlet.
        Route::post('/stok', [OutletController::class, 'updateStok'])->name('stok');
        // Ubah status buka atau tutup toko.
        Route::post('/status', [OutletController::class, 'ubahStatus'])->name('status');
        // Kirim permintaan restock ke gudang pusat.
        Route::post('/request-stok', [OutletController::class, 'kirimRequest'])->name('request_stok');
        // Riwayat request restock, dipanggil berkala via AJAX.
        Route::get('/riwayat-request', [OutletController::class, 'riwayatRequest'])->name('riwayat_request');
        // Halaman pesanan web masuk.
        Route::get('/pesanan-masuk', [OutletController::class, 'pesananMasuk'])->name('pesanan_masuk');
        // Perbarui status pesanan web.
        Route::post('/pesanan-masuk/status', [OutletController::class, 'updateStatusPesanan'])->name('pesanan_masuk.status');
        // Kirim balasan chat outlet untuk sebuah pesanan.
        Route::post('/pesanan-masuk/chat', [OutletController::class, 'kirimChat'])->name('pesanan_masuk.chat');
        // Halaman laporan keuangan outlet.
        Route::get('/laporan', [OutletController::class, 'laporan'])->name('laporan');
        // Simpan laporan keuangan harian.
        Route::post('/laporan', [OutletController::class, 'simpanLaporan'])->name('laporan.simpan');
        // Proses logout.
        Route::post('/logout', [OutletController::class, 'logout'])->name('logout');
    });
});

// =====================================================================
// 4. AREA MITRA
// =====================================================================
Route::prefix('mitra')->name('mitra.')->group(function () {
    // Halaman login mitra (dapat diakses tanpa login).
    Route::get('/login', [MitraController::class, 'tampilLogin'])->name('login');
    Route::post('/login', [MitraController::class, 'prosesLogin'])->name('login.proses');

    // Rute yang wajib login mitra.
    Route::middleware('jaga.mitra')->group(function () {
        // Halaman info stok gudang untuk mitra.
        Route::get('/', [MitraController::class, 'index'])->name('index');
        // Endpoint JSON stok ringkas untuk pembaruan langsung.
        Route::get('/cek-stok', [MitraController::class, 'cekStok'])->name('cek_stok');
        // Proses logout.
        Route::post('/logout', [MitraController::class, 'logout'])->name('logout');
    });
});

// =====================================================================
// 5. AREA OWNER
// =====================================================================
Route::prefix('owner')->name('owner.')->group(function () {
    // Halaman login owner (dapat diakses tanpa login).
    Route::get('/login', [OwnerController::class, 'tampilLogin'])->name('login');
    Route::post('/login', [OwnerController::class, 'prosesLogin'])->name('login.proses');

    // Rute yang wajib login owner.
    Route::middleware('jaga.owner')->group(function () {
        // Dashboard utama owner.
        Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard.alt');

        // Modul kelola menu.
        Route::get('/menu', [OwnerController::class, 'menuIndex'])->name('menu.index');
        Route::get('/menu/form/{id?}', [OwnerController::class, 'menuForm'])->name('menu.form');
        Route::post('/menu/simpan', [OwnerController::class, 'menuSimpan'])->name('menu.simpan');
        Route::post('/menu/hapus/{id}', [OwnerController::class, 'menuHapus'])->name('menu.hapus');

        // Modul kelola lokasi.
        Route::get('/lokasi', [OwnerController::class, 'lokasiIndex'])->name('lokasi.index');
        Route::get('/lokasi/form/{id?}', [OwnerController::class, 'lokasiForm'])->name('lokasi.form');
        Route::post('/lokasi/simpan', [OwnerController::class, 'lokasiSimpan'])->name('lokasi.simpan');
        Route::post('/lokasi/hapus/{id}', [OwnerController::class, 'lokasiHapus'])->name('lokasi.hapus');

        // Modul aktivasi outlet dan karyawan.
        Route::get('/outlet-karyawan', [OwnerController::class, 'outletKaryawan'])->name('outlet_karyawan');
        Route::post('/outlet-karyawan/aktifkan', [OwnerController::class, 'outletAktifkan'])->name('outlet_karyawan.aktifkan');
        Route::post('/outlet-karyawan/update', [OwnerController::class, 'outletUpdate'])->name('outlet_karyawan.update');

        // Modul stok gudang pusat.
        Route::get('/stok-gudang', [OwnerController::class, 'stokGudang'])->name('stok_gudang');
        Route::post('/stok-gudang/tambah', [OwnerController::class, 'stokGudangTambah'])->name('stok_gudang.tambah');
        Route::post('/stok-gudang/edit', [OwnerController::class, 'stokGudangEdit'])->name('stok_gudang.edit');
        Route::post('/stok-gudang/hapus/{id}', [OwnerController::class, 'stokGudangHapus'])->name('stok_gudang.hapus');

        // Modul permintaan stok dari outlet.
        Route::get('/request-stok', [OwnerController::class, 'requestStok'])->name('request_stok');
        Route::post('/request-stok/approve', [OwnerController::class, 'requestStokApprove'])->name('request_stok.approve');
        Route::post('/request-stok/tolak', [OwnerController::class, 'requestStokTolak'])->name('request_stok.tolak');

        // Modul pengaturan akun admin gudang.
        Route::get('/atur-gudang', [OwnerController::class, 'aturGudang'])->name('atur_gudang');
        Route::post('/atur-gudang', [OwnerController::class, 'aturGudangSimpan'])->name('atur_gudang.simpan');

        // Modul laporan keuangan.
        Route::get('/laporan-full', [OwnerController::class, 'laporanFull'])->name('laporan_full');
        // Modul riwayat penjualan web.
        Route::get('/riwayat-penjualan', [OwnerController::class, 'riwayatPenjualan'])->name('riwayat_penjualan');
        // Modul log aktivitas karyawan.
        Route::get('/log-aktivitas', [OwnerController::class, 'logAktivitas'])->name('log_aktivitas');

        // Modul profil perusahaan.
        Route::get('/profil', [OwnerController::class, 'profil'])->name('profil');
        Route::post('/profil', [OwnerController::class, 'profilSimpan'])->name('profil.simpan');

        // Modul data kemitraan.
        Route::get('/kemitraan', [OwnerController::class, 'kemitraan'])->name('kemitraan');
        Route::post('/kemitraan/hapus/{id}', [OwnerController::class, 'kemitraanHapus'])->name('kemitraan.hapus');

        // Modul akun mitra.
        Route::get('/akun-mitra', [OwnerController::class, 'akunMitra'])->name('akun_mitra');
        Route::post('/akun-mitra/tambah', [OwnerController::class, 'akunMitraTambah'])->name('akun_mitra.tambah');
        Route::post('/akun-mitra/hapus/{id}', [OwnerController::class, 'akunMitraHapus'])->name('akun_mitra.hapus');

        // Proses logout.
        Route::post('/logout', [OwnerController::class, 'logout'])->name('logout');
    });
});
