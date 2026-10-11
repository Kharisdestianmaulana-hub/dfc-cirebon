<?php

/**
 * Skrip bantu sekali pakai (bukan bagian aplikasi).
 *
 * Membaca basis data asli DFC Cirebon (dfc_asli, hasil impor berkas
 * if0_40861026_dfc_db.sql) lalu membangkitkan seeder Laravel
 * database/seeders/DataAsliSeeder.php yang memuat seluruh data tersebut.
 *
 * Dengan cara ini data asli dapat direproduksi lewat
 * php artisan migrate:fresh --seed.
 */

$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=dfc_asli;charset=utf8mb4', 'root', '');

/** Membersihkan karakter rusak akibat salah encoding pada peladen lama. */
function bersih($nilai)
{
    if ($nilai === null) {
        return null;
    }

    $nilai = (string) $nilai;

    // Peladen lama menyimpan tanda pisah sebagai urutan rusak berikut.
    // Urutan ini muncul karena karakter pengganti U+FFFD pernah dibaca
    // sebagai latin1 lalu disimpan kembali sebagai UTF-8.
    $nilai = str_replace("\u{00EF}\u{00BF}\u{00BD}", '-', $nilai);

    // Berjaga-jaga bila masih ada karakter pengganti tunggal.
    $nilai = str_replace("\u{FFFD}", '-', $nilai);

    return $nilai;
}

/** Mengubah nilai menjadi kode PHP yang aman. */
function kode($nilai)
{
    return var_export($nilai, true);
}

/** Mengubah satu baris asosiatif menjadi potongan kode PHP. */
function baris(array $data)
{
    $bagian = [];
    foreach ($data as $kunci => $nilai) {
        $bagian[] = kode($kunci).' => '.kode($nilai);
    }

    return '            ['.implode(', ', $bagian).'],';
}

/** Mengambil seluruh baris dari sebuah tabel di basis data asli. */
function ambil(PDO $pdo, string $tabel): array
{
    $hasil = $pdo->query("SELECT * FROM `{$tabel}`")->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function ($baris) {
        return array_map('bersih', $baris);
    }, $hasil);
}

$sekarang = date('Y-m-d H:i:s');

$keluaran = [];
$keluaran[] = '<?php';
$keluaran[] = '';
$keluaran[] = 'namespace Database\Seeders;';
$keluaran[] = '';
$keluaran[] = 'use Illuminate\Database\Seeder;';
$keluaran[] = 'use Illuminate\Support\Facades\DB;';
$keluaran[] = 'use Illuminate\Support\Facades\Hash;';
$keluaran[] = '';
$keluaran[] = '/**';
$keluaran[] = ' * Seeder data asli DFC Cirebon.';
$keluaran[] = ' *';
$keluaran[] = ' * Dibangkitkan dari basis data produksi yang dipakai di peladen';
$keluaran[] = ' * if0_40861026_dfc_db. Memuat seluruh akun, menu, lokasi, pengaturan';
$keluaran[] = ' * toko, stok, pesanan, percakapan, dan riwayat aktivitas.';
$keluaran[] = ' *';
$keluaran[] = ' * Kata sandi admin gudang pada basis data lama masih berupa teks biasa,';
$keluaran[] = ' * sehingga di seeder ini di-hash ulang memakai bcrypt.';
$keluaran[] = ' */';
$keluaran[] = 'class DataAsliSeeder extends Seeder';
$keluaran[] = '{';
$keluaran[] = '    public function run(): void';
$keluaran[] = '    {';
$keluaran[] = "        \$sekarang = date('Y-m-d H:i:s');";
$keluaran[] = '';

// ---------------------------------------------------------------- admin gudang
$adminGudang = ambil($pdo, 'admin_gudang');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // AKUN ADMIN GUDANG PUSAT';
$keluaran[] = '        // =====================================================';
foreach ($adminGudang as $r) {
    $sandi = (string) ($r['password'] ?? '');

    // Kata sandi lama tersimpan sebagai teks biasa, jadi wajib di-hash ulang.
    $kodeSandi = (str_starts_with($sandi, '$2y$') || str_starts_with($sandi, '$2a$'))
        ? kode($sandi)
        : 'Hash::make('.kode($sandi).')';

    $keluaran[] = '        DB::table(\'admin_gudang\')->insert([';
    $keluaran[] = '            \'id\' => '.(int) $r['id'].',';
    $keluaran[] = '            \'username\' => '.kode($r['username']).',';
    $keluaran[] = '            \'password\' => '.$kodeSandi.',';
    $keluaran[] = '            \'nama_lengkap\' => '.kode($r['nama_lengkap'] ?? null).',';
    $keluaran[] = '            \'created_at\' => $sekarang,';
    $keluaran[] = '            \'updated_at\' => $sekarang,';
    $keluaran[] = '        ]);';
    $keluaran[] = '';
}

// ---------------------------------------------------------------------- admin
$admin = ambil($pdo, 'admin');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // AKUN KARYAWAN OUTLET';
$keluaran[] = '        //';
$keluaran[] = '        // Pada basis data asli, kata sandi akun outlet tersimpan sebagai';
$keluaran[] = '        // hash bcrypt yang teks aslinya tidak diketahui lagi. Agar aplikasi';
$keluaran[] = '        // tetap dapat dipakai dan diuji, seluruh akun outlet diberi kata';
$keluaran[] = '        // sandi bawaan yang sama. Owner dapat menggantinya kapan saja';
$keluaran[] = '        // melalui panel owner pada modul outlet dan karyawan.';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $sandiOutlet = \'outlet123\';';
$keluaran[] = '';
foreach ($admin as $r) {
    $keluaran[] = '        DB::table(\'admin\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'username\' => '.kode($r['username']).
        ', \'password\' => Hash::make($sandiOutlet)'.
        ', \'nama_outlet\' => '.kode($r['nama_outlet'] ?? null).
        ', \'kode_outlet\' => '.kode($r['kode_outlet'] ?? null).
        ', \'nomor_wa\' => '.kode($r['nomor_wa'] ?? null).
        ', \'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
}
$keluaran[] = '';

// ---------------------------------------------------------------- akun mitra
$akunMitra = ambil($pdo, 'akun_mitra');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // AKUN MITRA';
$keluaran[] = '        //';
$keluaran[] = '        // Kata sandi mitra juga disetel ulang dengan pola yang mudah';
$keluaran[] = '        // diingat, yaitu nama akun diikuti angka 123.';
$keluaran[] = '        // =====================================================';
foreach ($akunMitra as $r) {
    $sandiMitra = $r['username'].'123';
    $keluaran[] = '        DB::table(\'akun_mitra\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'username\' => '.kode($r['username']).
        ', \'password\' => Hash::make('.kode($sandiMitra).')'.
        ', \'nama_mitra\' => '.kode($r['nama_mitra']).
        ', \'created_at\' => '.kode($r['created_at'] ?? $sekarang).
        ', \'updated_at\' => $sekarang]);';
}
$keluaran[] = '';

// --------------------------------------------------------------------- lokasi
$lokasi = ambil($pdo, 'lokasi');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // TITIK USAHA: PUSAT, OUTLET, MITRA, DAN CABANG';
$keluaran[] = '        // =====================================================';
foreach ($lokasi as $r) {
    $keluaran[] = '        DB::table(\'lokasi\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'nama\' => '.kode($r['nama']).
        ', \'alamat\' => '.kode($r['alamat']).
        ', \'map_query\' => '.kode($r['map_query'] ?? null).
        ', \'kategori\' => '.kode($r['kategori']).
        ', \'latitude\' => '.kode($r['latitude'] ?? null).
        ', \'longitude\' => '.kode($r['longitude'] ?? null).
        ', \'telepon\' => '.kode($r['telepon'] ?? null).
        ', \'username_admin\' => '.kode($r['username_admin'] ?? null).
        ', \'mitra_username\' => '.kode($r['mitra_username'] ?? null).
        ', \'qris_image\' => '.kode($r['qris_image'] ?? null).
        ', \'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
}
$keluaran[] = '';

// ----------------------------------------------------------------------- menu
$menu = ambil($pdo, 'menu');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // MENU JUAL';
$keluaran[] = '        // =====================================================';
foreach ($menu as $r) {
    $keluaran[] = '        DB::table(\'menu\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'nama\' => '.kode($r['nama']).
        ', \'harga\' => '.(int) $r['harga'].
        ', \'gambar\' => '.kode($r['gambar'] ?? null).
        ', \'kategori\' => '.kode($r['kategori'] ?? null).
        ', \'pemilik_menu\' => '.kode($r['pemilik_menu'] ?? 'pusat').
        ', \'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
}
$keluaran[] = '';

// ------------------------------------------------------------ stok per outlet
$kolomStok = [];
foreach (array_keys($menu[0] ?? []) as $kolom) {
    if (str_starts_with($kolom, 'stok_')) {
        $kolomStok[] = $kolom;
    }
}
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // STOK TIAP MENU PER OUTLET';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $stokOutlet = [';
foreach ($menu as $r) {
    foreach ($kolomStok as $kolom) {
        $kodeOutlet = substr($kolom, 5);
        $nilai = (int) ($r[$kolom] ?? 0);
        $keluaran[] = '            [\'menu_id\' => '.(int) $r['id'].", 'kode_outlet' => ".kode($kodeOutlet).", 'stok' => {$nilai}],";
    }
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($stokOutlet as $s) {';
$keluaran[] = '            DB::table(\'stok_outlet\')->insert($s + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ------------------------------------------------------------ pengaturan toko
$pengaturan = ambil($pdo, 'pengaturan_toko');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // PENGATURAN STATUS TOKO TIAP OUTLET';
$keluaran[] = '        // =====================================================';
foreach ($pengaturan as $r) {
    $keluaran[] = '        DB::table(\'pengaturan_toko\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'kode_outlet\' => '.kode($r['kode_outlet']).
        ', \'nama_outlet\' => '.kode($r['nama_outlet'] ?? '').
        ', \'status\' => '.kode($r['status'] ?? 'tutup').
        ', \'no_hp\' => null'.
        ', \'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
}
$keluaran[] = '';

// --------------------------------------------------------- profil perusahaan
$profil = ambil($pdo, 'profil_perusahaan');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // PROFIL PERUSAHAAN';
$keluaran[] = '        // =====================================================';
foreach ($profil as $r) {
    $keluaran[] = '        DB::table(\'profil_perusahaan\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'sejarah\' => '.kode($r['sejarah']).
        ', \'visi\' => '.kode($r['visi']).
        ', \'misi\' => '.kode($r['misi']).
        ', \'created_at\' => $sekarang, \'updated_at\' => '.kode($r['updated_at'] ?? $sekarang).']);';
}
$keluaran[] = '';

// ------------------------------------------------------------------ stok gudang
$stokGudang = ambil($pdo, 'stok_gudang');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // MASTER STOK GUDANG PUSAT';
$keluaran[] = '        // =====================================================';
foreach ($stokGudang as $r) {
    $keluaran[] = '        DB::table(\'stok_gudang\')->insert(['.
        '\'id\' => '.(int) $r['id'].
        ', \'mitra_username\' => '.kode($r['mitra_username'] ?? null).
        ', \'kategori\' => '.kode($r['kategori'] ?? null).
        ', \'nama_barang\' => '.kode($r['nama_barang']).
        ', \'stok\' => '.(int) $r['stok'].
        ', \'satuan\' => '.kode($r['satuan'] ?? null).
        ', \'created_at\' => '.kode($r['updated_at'] ?? $sekarang).
        ', \'updated_at\' => '.kode($r['updated_at'] ?? $sekarang).']);';
}
$keluaran[] = '';

// ----------------------------------------------------------------- riwayat stok
$riwayatStok = ambil($pdo, 'riwayat_stok');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // RIWAYAT PERUBAHAN STOK';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $riwayatStok = [';
foreach ($riwayatStok as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'mitra_username' => $r['mitra_username'] ?? null,
        'nama_barang' => $r['nama_barang'],
        'stok_lama' => (int) $r['stok_lama'],
        'stok_baru' => (int) $r['stok_baru'],
        'selisih' => (int) $r['selisih'],
        'admin' => $r['admin'],
        'waktu' => $r['waktu'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($riwayatStok as $r) {';
$keluaran[] = '            DB::table(\'riwayat_stok\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ----------------------------------------------------------------- request stok
$requestStok = ambil($pdo, 'request_stok');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // PERMINTAAN STOK DARI OUTLET';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $requestStok = [';
foreach ($requestStok as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'nama_outlet' => $r['nama_outlet'],
        'nama_barang' => $r['nama_barang'],
        'jumlah' => (int) $r['jumlah'],
        'satuan' => $r['satuan'],
        'status' => $r['status'] ?? 'Pending',
        'alasan_tolak' => $r['alasan_tolak'],
        'waktu_request' => $r['waktu_request'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($requestStok as $r) {';
$keluaran[] = '            DB::table(\'request_stok\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ------------------------------------------------------------------ penjualan
$penjualan = ambil($pdo, 'penjualan_harian');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // CATATAN PENJUALAN HARIAN';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $penjualan = [';
foreach ($penjualan as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'tanggal' => $r['tanggal'],
        'waktu' => $r['waktu'],
        'kode_outlet' => $r['kode_outlet'],
        'nama_menu' => $r['nama_menu'],
        'qty' => (int) $r['qty'],
        'catatan' => $r['catatan'],
        'harga_satuan' => (int) $r['harga_satuan'],
        'subtotal' => (int) $r['subtotal'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($penjualan as $r) {';
$keluaran[] = '            DB::table(\'penjualan_harian\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ------------------------------------------------------------------- keuangan
$keuangan = ambil($pdo, 'laporan_keuangan');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // LAPORAN KEUANGAN';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $keuangan = [';
foreach ($keuangan as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'tanggal' => $r['tanggal'],
        'kode_outlet' => $r['kode_outlet'],
        'omzet_kotor' => (int) $r['omzet_kotor'],
        'pengeluaran' => (int) $r['pengeluaran'],
        'omzet_bersih' => (int) $r['omzet_bersih'],
        'catatan' => $r['catatan'],
        'waktu_input' => $r['waktu_input'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($keuangan as $r) {';
$keluaran[] = '            DB::table(\'laporan_keuangan\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// --------------------------------------------------------------------- pesanan
$pesanan = ambil($pdo, 'transaksi_pesanan');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // TRANSAKSI PESANAN KONSUMEN';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $pesanan = [';
foreach ($pesanan as $r) {
    $keluaran[] = baris([
        'order_id' => $r['order_id'],
        'nama_pemesan' => $r['nama_pemesan'],
        'no_hp' => $r['no_hp'],
        'kode_outlet' => $r['kode_outlet'],
        'total_bayar' => (int) $r['total_bayar'],
        'metode_bayar' => $r['metode_bayar'],
        'detail_pesanan' => $r['detail_pesanan'],
        'status_pesanan' => $r['status_pesanan'],
        'waktu_pesan' => $r['waktu_pesan'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($pesanan as $r) {';
$keluaran[] = '            DB::table(\'transaksi_pesanan\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ----------------------------------------------------------------------- chat
$chat = ambil($pdo, 'chat_pesanan');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // PERCAKAPAN PESANAN ANTARA KONSUMEN DAN OUTLET';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $chat = [';
foreach ($chat as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'order_id' => $r['order_id'],
        'pengirim' => $r['pengirim'],
        'pesan' => $r['pesan'],
        'is_read' => (int) $r['is_read'],
        'waktu' => $r['waktu'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($chat as $r) {';
$keluaran[] = '            DB::table(\'chat_pesanan\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// ----------------------------------------------------------------- pengajuan
$pengajuan = ambil($pdo, 'pengajuan_mitra');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // PENGAJUAN KEMITRAAN';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $pengajuan = [';
foreach ($pengajuan as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'nama' => $r['nama'],
        'whatsapp' => $r['whatsapp'],
        'lokasi_usaha' => $r['lokasi_usaha'],
        'pesan' => $r['pesan'],
        'status' => $r['status'] ?? 'Pending',
        'tanggal' => $r['tanggal'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($pengajuan as $r) {';
$keluaran[] = '            DB::table(\'pengajuan_mitra\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '';

// --------------------------------------------------------------- log aktivitas
$log = ambil($pdo, 'log_aktivitas');
$keluaran[] = '        // =====================================================';
$keluaran[] = '        // LOG AKTIVITAS SISTEM';
$keluaran[] = '        // =====================================================';
$keluaran[] = '        $log = [';
foreach ($log as $r) {
    $keluaran[] = baris([
        'id' => (int) $r['id'],
        'waktu' => $r['waktu'],
        'pelaku' => $r['pelaku'],
        'aksi' => $r['aksi'],
        'detail' => $r['detail'],
    ]);
}
$keluaran[] = '        ];';
$keluaran[] = '        foreach ($log as $r) {';
$keluaran[] = '            DB::table(\'log_aktivitas\')->insert($r + [\'created_at\' => $sekarang, \'updated_at\' => $sekarang]);';
$keluaran[] = '        }';
$keluaran[] = '    }';
$keluaran[] = '}';
$keluaran[] = '';

file_put_contents(
    __DIR__.'/database/seeders/DataAsliSeeder.php',
    implode("\n", $keluaran)
);

echo "Seeder dibangkitkan: database/seeders/DataAsliSeeder.php\n";
echo 'Baris: '.count($keluaran)."\n";
