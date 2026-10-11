<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder data asli DFC Cirebon.
 *
 * Dibangkitkan dari basis data produksi yang dipakai di peladen
 * if0_40861026_dfc_db. Memuat seluruh akun, menu, lokasi, pengaturan
 * toko, stok, pesanan, percakapan, dan riwayat aktivitas.
 *
 * Kata sandi admin gudang pada basis data lama masih berupa teks biasa,
 * sehingga di seeder ini di-hash ulang memakai bcrypt.
 */
class DataAsliSeeder extends Seeder
{
    public function run(): void
    {
        $sekarang = date('Y-m-d H:i:s');

        // =====================================================
        // AKUN ADMIN GUDANG PUSAT
        // =====================================================
        DB::table('admin_gudang')->insert([
            'id' => 1,
            'username' => 'Gudang',
            'password' => Hash::make('gudang123'),
            'nama_lengkap' => 'Kepala Gudang',
            'created_at' => $sekarang,
            'updated_at' => $sekarang,
        ]);

        // =====================================================
        // AKUN KARYAWAN OUTLET
        //
        // Pada basis data asli, kata sandi akun outlet tersimpan sebagai
        // hash bcrypt yang teks aslinya tidak diketahui lagi. Agar aplikasi
        // tetap dapat dipakai dan diuji, seluruh akun outlet diberi kata
        // sandi bawaan yang sama. Owner dapat menggantinya kapan saja
        // melalui panel owner pada modul outlet dan karyawan.
        // =====================================================
        $sandiOutlet = 'outlet123';

        DB::table('admin')->insert(['id' => 4, 'username' => 'sutawinangun', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 5, 'username' => 'cipto', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 6, 'username' => 'klayan', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 7, 'username' => 'sunyaragi', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 8, 'username' => 'pemuda', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 9, 'username' => 'palimanan', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 10, 'username' => 'jambe', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 11, 'username' => 'gegesik', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 12, 'username' => 'kaplongan', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 13, 'username' => 'tenajar', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 14, 'username' => 'pamengkang', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 15, 'username' => 'damar', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => NULL, 'kode_outlet' => NULL, 'nomor_wa' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('admin')->insert(['id' => 17, 'username' => 'kharis', 'password' => Hash::make($sandiOutlet), 'nama_outlet' => 'Mitra 4 (Fajar) - Sumber', 'kode_outlet' => 'kharis', 'nomor_wa' => '08123456789', 'created_at' => $sekarang, 'updated_at' => $sekarang]);

        // =====================================================
        // AKUN MITRA
        //
        // Kata sandi mitra juga disetel ulang dengan pola yang mudah
        // diingat, yaitu nama akun diikuti angka 123.
        // =====================================================
        DB::table('akun_mitra')->insert(['id' => 1, 'username' => 'kharis', 'password' => Hash::make('kharis123'), 'nama_mitra' => 'Kharis', 'created_at' => '2026-01-12 17:55:07', 'updated_at' => $sekarang]);
        DB::table('akun_mitra')->insert(['id' => 2, 'username' => 'fajar', 'password' => Hash::make('fajar123'), 'nama_mitra' => 'Fajar', 'created_at' => '2026-02-20 10:06:58', 'updated_at' => $sekarang]);

        // =====================================================
        // TITIK USAHA: PUSAT, OUTLET, MITRA, DAN CABANG
        // =====================================================
        DB::table('lokasi')->insert(['id' => 1, 'nama' => 'Kantor Pusat DFC', 'alamat' => 'Jl. Perintis, Pecilon, RT 04 RW 01, Sutawinangun, Cirebon', 'map_query' => 'Jl. Perintis Pecilon Sutawinangun Cirebon', 'kategori' => 'Pusat', 'latitude' => '-6.71500335210028', 'longitude' => '108.54347653974567', 'telepon' => '6281xxx', 'username_admin' => 'pusat', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 2, 'nama' => 'Outlet 1 - Sutawinangun', 'alamat' => 'Indomaret Sutawinangun, Pecilon, Cirebon', 'map_query' => 'Indomaret Sutawinangun Cirebon', 'kategori' => 'Outlet', 'latitude' => '-6.715738045640903', 'longitude' => '108.54595818466152', 'telepon' => '081222453572', 'username_admin' => 'sutawinangun', 'mitra_username' => NULL, 'qris_image' => 'qris_6a622a01e291e.jpeg', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 3, 'nama' => 'Outlet 2 - Cipto MK', 'alamat' => 'Jl. Cipto MK, sebelah Apotik Prima Sejati Cipto, Cirebon', 'map_query' => 'Apotik Prima Sejati Cipto Cirebon', 'kategori' => 'Outlet', 'latitude' => '-6.716911772219276', 'longitude' => '108.55076828991093', 'telepon' => NULL, 'username_admin' => 'cipto', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 4, 'nama' => 'Outlet 3 - Klayan', 'alamat' => 'Jl. Sunan Gunung Jati, Klayan, Cirebon', 'map_query' => 'Jl. Sunan Gunung Jati Klayan Cirebon', 'kategori' => 'Outlet', 'latitude' => '-6.623849', 'longitude' => '108.522778', 'telepon' => NULL, 'username_admin' => 'klayan', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 5, 'nama' => 'Outlet 4 - Sunyaragi', 'alamat' => 'Samping Alfamart Taman Sunyaragi, Kota Cirebon', 'map_query' => 'Alfamart Taman Sunyaragi Cirebon', 'kategori' => 'Outlet', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'sunyaragi', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 6, 'nama' => 'Mitra 1 (Riki) - Pemuda', 'alamat' => 'Jl. Pemuda, Sunyaragi, samping Kodim, Kota Cirebon', 'map_query' => 'Kodim 0614 Kota Cirebon', 'kategori' => 'Mitra', 'latitude' => '-6.730672067634024', 'longitude' => '108.54551507456866', 'telepon' => NULL, 'username_admin' => NULL, 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 7, 'nama' => 'Mitra 2 (Agus) - Palimanan', 'alamat' => 'Jl. Raya Gempol, Palimanan, Cirebon', 'map_query' => 'Jl. Raya Gempol Palimanan Cirebon', 'kategori' => 'Mitra', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => NULL, 'mitra_username' => 'kharis', 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 8, 'nama' => 'Cabang Jambe', 'alamat' => 'Jl. Raya Jambe, Desa Jambe, Kec. Kertasmaya, Kab. Indramayu', 'map_query' => 'Balai Desa Jambe Indramayu', 'kategori' => 'Cabang', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'jambe', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 9, 'nama' => 'Cabang Gegesik', 'alamat' => 'Jl. Raya Arjawinangun-Gegesik, Desa Bayalangu Kidul, Kec. Gegesik, Kab. Cirebon', 'map_query' => 'Bayalangu Kidul Gegesik Cirebon', 'kategori' => 'Cabang', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'gegesik', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 10, 'nama' => 'Cabang Kaplongan', 'alamat' => 'Jl. Raya Kaplongan, Desa Kaplongan Lor, Kec. Kedokan, Kab. Indramayu', 'map_query' => 'Kaplongan Lor Indramayu', 'kategori' => 'Cabang', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'kaplongan', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 11, 'nama' => 'Cabang Tenajar', 'alamat' => 'Jl. Raya Tenajar-Kertasmaya, Desa Tenajar Kidul, Kec. Kertasmaya, Kab. Indramayu', 'map_query' => 'Tenajar Kidul Kertasmaya Indramayu', 'kategori' => 'Cabang', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'tenajar', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 12, 'nama' => 'Cabang Pamengkang', 'alamat' => 'Jl. Raya Pamengkang, depan Masjid Jami Baiturrahman, Cirebon', 'map_query' => 'Masjid Jami Baiturrahman Pamengkang Cirebon', 'kategori' => 'Cabang', 'latitude' => NULL, 'longitude' => NULL, 'telepon' => NULL, 'username_admin' => 'pamengkang', 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 14, 'nama' => 'Mitra 3 (Damar) - Plered', 'alamat' => 'Jl. Trusmi, Weru Lor, Samping Batik Trusmi', 'map_query' => '', 'kategori' => 'Mitra', 'latitude' => '-6.701266086118018', 'longitude' => '108.50774847512422', 'telepon' => '08123456789', 'username_admin' => NULL, 'mitra_username' => NULL, 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('lokasi')->insert(['id' => 15, 'nama' => 'Mitra 4 (Fajar) - Sumber', 'alamat' => 'Jl. Sumber 123', 'map_query' => '', 'kategori' => 'Mitra', 'latitude' => '-6.710985059054025', 'longitude' => '108.50500575993497', 'telepon' => '08123456789', 'username_admin' => 'kharis', 'mitra_username' => 'kharis', 'qris_image' => NULL, 'created_at' => $sekarang, 'updated_at' => $sekarang]);

        // =====================================================
        // MENU JUAL
        // =====================================================
        DB::table('menu')->insert(['id' => 1, 'nama' => 'Sayap (Wings)', 'harga' => 8000, 'gambar' => 'img/1766537272_Sayap.png', 'kategori' => 'satuan', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 2, 'nama' => 'Paha Bawah', 'harga' => 8000, 'gambar' => 'img/1766537263_OriginalPaha.png', 'kategori' => 'satuan', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 3, 'nama' => 'Dada', 'harga' => 10000, 'gambar' => 'img/1766537251_Dada.png', 'kategori' => 'satuan', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 4, 'nama' => 'Paha Atas', 'harga' => 10000, 'gambar' => 'img/1766537240_PahaAtas.png', 'kategori' => 'satuan', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 5, 'nama' => 'Nasi Putih', 'harga' => 3000, 'gambar' => 'img/1766537225_Nasi.png', 'kategori' => 'nasi', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 7, 'nama' => 'Geprek', 'harga' => 13000, 'gambar' => 'img/1767944604_Geprek.webp', 'kategori' => 'paket', 'pemilik_menu' => 'pusat', 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('menu')->insert(['id' => 14, 'nama' => 'Es Doger Segari', 'harga' => 5000, 'gambar' => 'img/1783928818_es-doger.jpg', 'kategori' => NULL, 'pemilik_menu' => 'Kharis', 'created_at' => $sekarang, 'updated_at' => $sekarang]);

        // =====================================================
        // STOK TIAP MENU PER OUTLET
        // =====================================================
        $stokOutlet = [
            ['menu_id' => 1, 'kode_outlet' => 'sutawinangun', 'stok' => 8],
            ['menu_id' => 1, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 1, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'sutawinangun', 'stok' => 10],
            ['menu_id' => 2, 'kode_outlet' => 'cipto', 'stok' => 5],
            ['menu_id' => 2, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 2, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'sutawinangun', 'stok' => 10],
            ['menu_id' => 3, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 3, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'sutawinangun', 'stok' => 10],
            ['menu_id' => 4, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 4, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'sutawinangun', 'stok' => 39],
            ['menu_id' => 5, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 5, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'sutawinangun', 'stok' => 10],
            ['menu_id' => 7, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 7, 'kode_outlet' => 'kharis', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'sutawinangun', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'cipto', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'klayan', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'sunyaragi', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'pemuda', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'palimanan', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'jambe', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'gegesik', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'kaplongan', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'tenajar', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'pamengkang', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'damar', 'stok' => 0],
            ['menu_id' => 14, 'kode_outlet' => 'kharis', 'stok' => 0],
        ];
        foreach ($stokOutlet as $s) {
            DB::table('stok_outlet')->insert($s + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // PENGATURAN STATUS TOKO TIAP OUTLET
        // =====================================================
        DB::table('pengaturan_toko')->insert(['id' => 4, 'kode_outlet' => 'sutawinangun', 'nama_outlet' => '', 'status' => 'buka', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 5, 'kode_outlet' => 'cipto', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 6, 'kode_outlet' => 'klayan', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 7, 'kode_outlet' => 'sunyaragi', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 8, 'kode_outlet' => 'pemuda', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 9, 'kode_outlet' => 'palimanan', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 10, 'kode_outlet' => 'jambe', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 11, 'kode_outlet' => 'gegesik', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 12, 'kode_outlet' => 'kaplongan', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 13, 'kode_outlet' => 'tenajar', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 14, 'kode_outlet' => 'pamengkang', 'nama_outlet' => '', 'status' => 'tutup', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 15, 'kode_outlet' => 'damar', 'nama_outlet' => '', 'status' => 'buka', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);
        DB::table('pengaturan_toko')->insert(['id' => 16, 'kode_outlet' => 'kharis', 'nama_outlet' => 'Mitra 4 (Fajar) - Sumber', 'status' => 'buka', 'no_hp' => null, 'created_at' => $sekarang, 'updated_at' => $sekarang]);

        // =====================================================
        // PROFIL PERUSAHAAN
        // =====================================================
        DB::table('profil_perusahaan')->insert(['id' => 1, 'sejarah' => 'Dexpress Fried Chicken (DFC) adalah UMKM kuliner asli Cirebon yang berdiri dengan semangat untuk menyajikan ayam goreng berkualitas tinggi namun dengan harga yang terjangkau bagi semua kalangan. Kami menggunakan bumbu rempah rahasia yang meresap hingga ke tulang.', 'visi' => 'Menjadi brand Fried Chicken lokal nomor 1 di Cirebon yang memberdayakan ekonomi mitra UMKM.', 'misi' => 'Menyajikan produk ayam goreng yang Halal, Higienis, dan Hemat. Membangun sistem kemitraan yang transparan.', 'created_at' => $sekarang, 'updated_at' => '2026-02-19 05:33:04']);

        // =====================================================
        // MASTER STOK GUDANG PUSAT
        // =====================================================
        DB::table('stok_gudang')->insert(['id' => 1, 'mitra_username' => NULL, 'kategori' => 'Ayam Marinasi', 'nama_barang' => 'Sayap', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-19 18:19:39', 'updated_at' => '2026-02-19 18:19:39']);
        DB::table('stok_gudang')->insert(['id' => 2, 'mitra_username' => NULL, 'kategori' => 'Ayam Marinasi', 'nama_barang' => 'Paha Bawah (Drumstick)', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-19 18:19:46', 'updated_at' => '2026-02-19 18:19:46']);
        DB::table('stok_gudang')->insert(['id' => 3, 'mitra_username' => NULL, 'kategori' => 'Ayam Marinasi', 'nama_barang' => 'Dada (Breast)', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-19 18:19:57', 'updated_at' => '2026-02-19 18:19:57']);
        DB::table('stok_gudang')->insert(['id' => 5, 'mitra_username' => NULL, 'kategori' => 'Bahan Baku', 'nama_barang' => 'Minyak Goreng', 'stok' => 100, 'satuan' => 'Liter', 'created_at' => '2026-02-19 18:20:20', 'updated_at' => '2026-02-19 18:20:20']);
        DB::table('stok_gudang')->insert(['id' => 11, 'mitra_username' => NULL, 'kategori' => '', 'nama_barang' => 'Tepung marinasi', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-19 12:20:31', 'updated_at' => '2026-02-19 12:20:31']);
        DB::table('stok_gudang')->insert(['id' => 7, 'mitra_username' => NULL, 'kategori' => 'Kemasan', 'nama_barang' => 'Paper Bag DFC', 'stok' => 100, 'satuan' => 'Pack', 'created_at' => '2026-02-19 12:20:45', 'updated_at' => '2026-02-19 12:20:45']);
        DB::table('stok_gudang')->insert(['id' => 8, 'mitra_username' => NULL, 'kategori' => 'Kemasan', 'nama_barang' => 'Dus Kotak Nasi', 'stok' => 1000, 'satuan' => 'Pack', 'created_at' => '2026-02-19 12:21:10', 'updated_at' => '2026-02-19 12:21:10']);
        DB::table('stok_gudang')->insert(['id' => 9, 'mitra_username' => NULL, 'kategori' => '', 'nama_barang' => 'Paha Atas', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-19 18:20:10', 'updated_at' => '2026-02-19 18:20:10']);
        DB::table('stok_gudang')->insert(['id' => 12, 'mitra_username' => NULL, 'kategori' => '', 'nama_barang' => 'Tepung marinasi', 'stok' => 100, 'satuan' => 'Kg', 'created_at' => '2026-02-20 09:57:33', 'updated_at' => '2026-02-20 09:57:33']);

        // =====================================================
        // RIWAYAT PERUBAHAN STOK
        // =====================================================
        $riwayatStok = [
            ['id' => 1, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 1000, 'stok_baru' => 2000, 'selisih' => 1000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-21 15:14:06'],
            ['id' => 2, 'mitra_username' => NULL, 'nama_barang' => 'Paper Bag DFC', 'stok_lama' => 1000, 'stok_baru' => 3000, 'selisih' => 2000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-21 15:18:03'],
            ['id' => 3, 'mitra_username' => NULL, 'nama_barang' => 'Dada (Breast)', 'stok_lama' => 60, 'stok_baru' => 1000, 'selisih' => 940, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-22 09:17:14'],
            ['id' => 4, 'mitra_username' => NULL, 'nama_barang' => 'Minyak Goreng Padat', 'stok_lama' => 100, 'stok_baru' => 2000, 'selisih' => 1900, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-22 09:30:04'],
            ['id' => 5, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 2000, 'stok_baru' => 3000, 'selisih' => 1000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-22 12:00:04'],
            ['id' => 6, 'mitra_username' => NULL, 'nama_barang' => 'Minyak Goreng Padat', 'stok_lama' => 2000, 'stok_baru' => 3000, 'selisih' => 1000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-23 09:18:02'],
            ['id' => 7, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 3000, 'stok_baru' => 2000, 'selisih' => -1000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-23 11:36:50'],
            ['id' => 8, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 2000, 'stok_baru' => 3000, 'selisih' => 1000, 'admin' => 'Kepala Gudang', 'waktu' => '2025-12-23 11:37:21'],
            ['id' => 9, 'mitra_username' => NULL, 'nama_barang' => 'Minyak Goreng', 'stok_lama' => 3000, 'stok_baru' => 0, 'selisih' => -3000, 'admin' => 'Kepala Gudang', 'waktu' => '2026-01-12 13:37:05'],
            ['id' => 10, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 3000, 'stok_baru' => 0, 'selisih' => -3000, 'admin' => 'Kepala Gudang', 'waktu' => '2026-01-12 13:37:11'],
            ['id' => 11, 'mitra_username' => NULL, 'nama_barang' => 'Paper Bag DFC', 'stok_lama' => 3000, 'stok_baru' => 0, 'selisih' => -3000, 'admin' => 'Kepala Gudang', 'waktu' => '2026-01-12 13:37:25'],
            ['id' => 12, 'mitra_username' => NULL, 'nama_barang' => 'Sayap', 'stok_lama' => 0, 'stok_baru' => 100, 'selisih' => 100, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 06:12:55'],
            ['id' => 13, 'mitra_username' => NULL, 'nama_barang' => 'Sayap', 'stok_lama' => 100, 'stok_baru' => 0, 'selisih' => -100, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 07:08:48'],
            ['id' => 14, 'mitra_username' => NULL, 'nama_barang' => 'Sayap', 'stok_lama' => 0, 'stok_baru' => 10, 'selisih' => 10, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:19:28'],
            ['id' => 15, 'mitra_username' => NULL, 'nama_barang' => 'Paha Bawah (Drumstick)', 'stok_lama' => 0, 'stok_baru' => 10, 'selisih' => 10, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:19:42'],
            ['id' => 16, 'mitra_username' => NULL, 'nama_barang' => 'Dada (Breast)', 'stok_lama' => 0, 'stok_baru' => 10, 'selisih' => 10, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:19:56'],
            ['id' => 17, 'mitra_username' => NULL, 'nama_barang' => 'Minyak Goreng', 'stok_lama' => 0, 'stok_baru' => 12, 'selisih' => 12, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:20:19'],
            ['id' => 18, 'mitra_username' => NULL, 'nama_barang' => 'Tepung marinasi', 'stok_lama' => 0, 'stok_baru' => 100, 'selisih' => 100, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:20:31'],
            ['id' => 19, 'mitra_username' => NULL, 'nama_barang' => 'Paper Bag DFC', 'stok_lama' => 0, 'stok_baru' => 100, 'selisih' => 100, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:20:45'],
            ['id' => 20, 'mitra_username' => NULL, 'nama_barang' => 'Dus Kotak Nasi', 'stok_lama' => 0, 'stok_baru' => 1000, 'selisih' => 1000, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:21:10'],
            ['id' => 21, 'mitra_username' => NULL, 'nama_barang' => 'Paha Atas', 'stok_lama' => 0, 'stok_baru' => 10, 'selisih' => 10, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 12:21:23'],
            ['id' => 22, 'mitra_username' => NULL, 'nama_barang' => 'Sayap', 'stok_lama' => 10, 'stok_baru' => 100, 'selisih' => 90, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 18:19:39'],
            ['id' => 23, 'mitra_username' => NULL, 'nama_barang' => 'Paha Bawah (Drumstick)', 'stok_lama' => 10, 'stok_baru' => 100, 'selisih' => 90, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 18:19:46'],
            ['id' => 24, 'mitra_username' => NULL, 'nama_barang' => 'Dada (Breast)', 'stok_lama' => 10, 'stok_baru' => 100, 'selisih' => 90, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 18:19:57'],
            ['id' => 25, 'mitra_username' => NULL, 'nama_barang' => 'Paha Atas', 'stok_lama' => 10, 'stok_baru' => 100, 'selisih' => 90, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 18:20:10'],
            ['id' => 26, 'mitra_username' => NULL, 'nama_barang' => 'Minyak Goreng', 'stok_lama' => 12, 'stok_baru' => 100, 'selisih' => 88, 'admin' => 'Owner (Edit)', 'waktu' => '2026-02-19 18:20:20'],
            ['id' => 27, 'mitra_username' => NULL, 'nama_barang' => 'Tepung marinasi', 'stok_lama' => 0, 'stok_baru' => 100, 'selisih' => 100, 'admin' => 'Owner (Baru)', 'waktu' => '2026-02-20 09:57:33'],
        ];
        foreach ($riwayatStok as $r) {
            DB::table('riwayat_stok')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // PERMINTAAN STOK DARI OUTLET
        // =====================================================
        $requestStok = [
            ['id' => 1, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Nasi Putih', 'jumlah' => 10, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-02-19 11:44:16'],
            ['id' => 2, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Nasi Putih', 'jumlah' => 10, 'satuan' => 'Pcs', 'status' => 'Ditolak', 'alasan_tolak' => 'Nasi Habis Harus dimasak dulu', 'waktu_request' => '2026-02-19 11:45:34'],
            ['id' => 3, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Nasi Putih', 'jumlah' => 10, 'satuan' => 'Pcs', 'status' => 'Ditolak', 'alasan_tolak' => 'Habis nasinya', 'waktu_request' => '2026-02-19 11:47:35'],
            ['id' => 4, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Ayam PS', 'jumlah' => 10, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-02-19 11:53:07'],
            ['id' => 5, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Dada', 'jumlah' => 15, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-02-19 13:57:52'],
            ['id' => 6, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Nasi Putih', 'jumlah' => 8, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-02-20 08:54:05'],
            ['id' => 7, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Tepung Bumbu', 'jumlah' => 1, 'satuan' => 'Kg', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-02-20 09:50:04'],
            ['id' => 8, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Paha bawah', 'jumlah' => 10, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-07-13 11:34:47'],
            ['id' => 9, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'nasi', 'jumlah' => 15, 'satuan' => 'Pcs', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-07-13 12:03:39'],
            ['id' => 10, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Tepung Bumbu', 'jumlah' => 1, 'satuan' => 'Kg', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-07-23 15:38:21'],
            ['id' => 11, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Tepung Bumbu', 'jumlah' => 1, 'satuan' => 'Kg', 'status' => 'Ditolak', 'alasan_tolak' => '', 'waktu_request' => '2026-07-23 15:46:02'],
            ['id' => 12, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'Sayap', 'jumlah' => 1, 'satuan' => 'Kg', 'status' => 'Disetujui', 'alasan_tolak' => '-', 'waktu_request' => '2026-07-23 16:02:20'],
            ['id' => 13, 'nama_outlet' => 'Outlet 1 - Sutawinangun', 'nama_barang' => 'paha atas', 'jumlah' => 2, 'satuan' => 'Kg', 'status' => 'Pending', 'alasan_tolak' => NULL, 'waktu_request' => '2026-07-23 16:06:31'],
        ];
        foreach ($requestStok as $r) {
            DB::table('request_stok')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // CATATAN PENJUALAN HARIAN
        // =====================================================
        $penjualan = [
            ['id' => 1, 'tanggal' => '2025-12-24', 'waktu' => '14:22:28', 'kode_outlet' => 'cipto', 'nama_menu' => 'Paha Bawah', 'qty' => 5, 'catatan' => '3 original, 2 pedas', 'harga_satuan' => 8000, 'subtotal' => 40000],
            ['id' => 2, 'tanggal' => '2025-12-24', 'waktu' => '14:40:51', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Sayap (Wings)', 'qty' => 2, 'catatan' => 'ori 1, pedes 1', 'harga_satuan' => 8000, 'subtotal' => 16000],
            ['id' => 3, 'tanggal' => '2026-02-20', 'waktu' => '06:25:17', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Sayap (Wings)', 'qty' => 5, 'catatan' => 'Pedas 2, Ori 3', 'harga_satuan' => 8000, 'subtotal' => 40000],
            ['id' => 4, 'tanggal' => '2026-02-20', 'waktu' => '09:52:10', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Sayap (Wings)', 'qty' => 1, 'catatan' => 'Pedas', 'harga_satuan' => 8000, 'subtotal' => 8000],
            ['id' => 5, 'tanggal' => '2026-02-20', 'waktu' => '09:52:35', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Sayap (Wings)', 'qty' => 1, 'catatan' => 'Pedas', 'harga_satuan' => 8000, 'subtotal' => 8000],
            ['id' => 6, 'tanggal' => '2026-02-20', 'waktu' => '09:53:00', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Nasi Putih', 'qty' => 1, 'catatan' => 'Original', 'harga_satuan' => 3000, 'subtotal' => 3000],
            ['id' => 7, 'tanggal' => '2026-07-13', 'waktu' => '11:35:46', 'kode_outlet' => 'sutawinangun', 'nama_menu' => 'Sayap (Wings)', 'qty' => 1, 'catatan' => 'pedas 1', 'harga_satuan' => 8000, 'subtotal' => 8000],
        ];
        foreach ($penjualan as $r) {
            DB::table('penjualan_harian')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // LAPORAN KEUANGAN
        // =====================================================
        $keuangan = [
            ['id' => 1, 'tanggal' => '2026-02-18', 'kode_outlet' => 'sutawinangun', 'omzet_kotor' => 20000000, 'pengeluaran' => 0, 'omzet_bersih' => 20000000, 'catatan' => '', 'waktu_input' => '2026-02-18 18:09:59'],
            ['id' => 2, 'tanggal' => '2026-02-19', 'kode_outlet' => 'sutawinangun', 'omzet_kotor' => 1000000, 'pengeluaran' => 0, 'omzet_bersih' => 1000000, 'catatan' => '', 'waktu_input' => '2026-02-19 12:18:13'],
            ['id' => 3, 'tanggal' => '2026-02-20', 'kode_outlet' => 'sutawinangun', 'omzet_kotor' => 1000000, 'pengeluaran' => 50000, 'omzet_bersih' => 950000, 'catatan' => 'Beli Gas', 'waktu_input' => '2026-02-20 09:51:20'],
        ];
        foreach ($keuangan as $r) {
            DB::table('laporan_keuangan')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // TRANSAKSI PESANAN KONSUMEN
        // =====================================================
        $pesanan = [
            ['order_id' => 'DFC-190226-3B8E', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'outlet 1 - sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 11:45:37'],
            ['order_id' => 'DFC-190226-0564', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'outlet 1 - sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 11:48:49'],
            ['order_id' => 'DFC-190226-F708', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 11:56:59'],
            ['order_id' => 'DFC-190226-ACAC', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 11:59:02'],
            ['order_id' => 'DFC-200226-F6FE', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 12:04:29'],
            ['order_id' => 'DFC-200226-8823', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 12:14:29'],
            ['order_id' => 'DFC-200226-EE13', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 13:00:01'],
            ['order_id' => 'DFC-200226-3434', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 13:36:50'],
            ['order_id' => 'DFC-200226-726A', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"Pedes semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 13:43:41'],
            ['order_id' => 'DFC-200226-659A', 'nama_pemesan' => 'Budi', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-20 14:32:33'],
            ['order_id' => 'DFC-210226-A354', 'nama_pemesan' => 'damar', 'no_hp' => '2152512', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":""},{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-02-21 16:44:01'],
            ['order_id' => 'DFC-110326-71FD', 'nama_pemesan' => 'kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"pedas semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-03-11 11:53:28'],
            ['order_id' => 'DFC-250426-FB0B', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-04-25 20:18:18'],
            ['order_id' => 'DFC-250426-14EC', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-04-25 20:18:48'],
            ['order_id' => 'DFC-250426-6F45', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-04-25 20:19:07'],
            ['order_id' => 'DFC-250426-3882', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-04-25 20:19:40'],
            ['order_id' => 'DFC-250426-23C1', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-04-25 20:21:26'],
            ['order_id' => 'DFC-080726-7600', 'nama_pemesan' => 'Cemeng', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 80000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":10,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-08 12:54:43'],
            ['order_id' => 'DFC-220726-F711', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"pedas semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 14:49:31'],
            ['order_id' => 'DFC-220726-0286', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"ori semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 15:13:49'],
            ['order_id' => 'DFC-220726-F414', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":2,"catatan":"1 pedas satu ori ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 19:46:11'],
            ['order_id' => 'DFC-220726-3698', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":2,"catatan":"1 pedas satu ori ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 19:47:23'],
            ['order_id' => 'DFC-220726-A3EC', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":2,"catatan":"pedas 1 ori 1 ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 19:51:53'],
            ['order_id' => 'DFC-220726-E846', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"pedas semua ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 20:02:11'],
            ['order_id' => 'DFC-220726-4C97', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":2,"catatan":"pedas 1 ori 1 ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 20:08:29'],
            ['order_id' => 'DFC-220726-5ED8', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 20000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Dada","harga":10000,"qty":2,"catatan":"pedas semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 20:18:14'],
            ['order_id' => 'DFC-220726-E787', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 20000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Dada","harga":10000,"qty":2,"catatan":"ori semua ya"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 20:37:03'],
            ['order_id' => 'DFC-220726-830F', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":"ori"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-22 22:50:58'],
            ['order_id' => 'DFC-230726-977E', 'nama_pemesan' => 'damar', 'no_hp' => '2152512', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 10000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Dada","harga":10000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 15:03:00'],
            ['order_id' => 'DFC-230726-B864', 'nama_pemesan' => 'da', 'no_hp' => '2152512', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 15:07:46'],
            ['order_id' => 'DFC-230726-CAE7', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":"pedas"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 15:10:17'],
            ['order_id' => 'DFC-230726-C4A0', 'nama_pemesan' => 'da', 'no_hp' => '2152512', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 15:20:53'],
            ['order_id' => 'DFC-230726-74C0', 'nama_pemesan' => 'Ayyas', 'no_hp' => '085329793332', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 15:38:28'],
            ['order_id' => 'DFC-230726-6CE4', 'nama_pemesan' => 'Ayyas', 'no_hp' => '085329793332', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 16:00:26'],
            ['order_id' => 'DFC-230726-5525', 'nama_pemesan' => 'Sukmadih', 'no_hp' => '085724778058', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'Cash', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 17:41:58'],
            ['order_id' => 'DFC-230726-D186', 'nama_pemesan' => 'da', 'no_hp' => '2152512', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 21:24:58'],
            ['order_id' => 'DFC-230726-3AF0', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 16000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":2,"catatan":"pedas semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 21:56:00'],
            ['order_id' => 'DFC-230726-0CDE', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":"pedas semua"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-23 22:03:03'],
            ['order_id' => 'DFC-230726-6AF3', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":"ORI"}]', 'status_pesanan' => 'Selesai', 'waktu_pesan' => '2026-07-24 09:45:36'],
            ['order_id' => 'DFC-230726-D920', 'nama_pemesan' => 'jon', 'no_hp' => '09998877665', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 20000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Dada","harga":10000,"qty":2,"catatan":""}]', 'status_pesanan' => 'Menunggu Konfirmasi', 'waktu_pesan' => '2026-07-24 10:10:36'],
            ['order_id' => 'DFC-230726-C796', 'nama_pemesan' => 'Kharis ', 'no_hp' => '051222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Sayap (Wings)","harga":8000,"qty":1,"catatan":"Pedas"}]', 'status_pesanan' => 'Menunggu Konfirmasi', 'waktu_pesan' => '2026-07-24 10:14:25'],
            ['order_id' => 'DFC-230726-3F8B', 'nama_pemesan' => 'damar', 'no_hp' => '08996070813', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Menunggu Konfirmasi', 'waktu_pesan' => '2026-07-24 10:22:34'],
            ['order_id' => 'DFC-240726-4D6A', 'nama_pemesan' => 'budi', 'no_hp' => '083455476', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 13000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Dada","harga":10000,"qty":1,"catatan":""},{"nama":"Nasi Putih","harga":3000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Menunggu Konfirmasi', 'waktu_pesan' => '2026-07-24 11:03:52'],
            ['order_id' => 'DFC-240726-E958', 'nama_pemesan' => 'Kharis', 'no_hp' => '081222453572', 'kode_outlet' => 'sutawinangun', 'total_bayar' => 8000, 'metode_bayar' => 'QRIS', 'detail_pesanan' => '[{"nama":"Paha Bawah","harga":8000,"qty":1,"catatan":""}]', 'status_pesanan' => 'Menunggu Konfirmasi', 'waktu_pesan' => '2026-07-24 11:17:44'],
        ];
        foreach ($pesanan as $r) {
            DB::table('transaksi_pesanan')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // PERCAKAPAN PESANAN ANTARA KONSUMEN DAN OUTLET
        // =====================================================
        $chat = [
            ['id' => 50, 'order_id' => 'DFC-230726-D920', 'pengirim' => 'konsumen', 'pesan' => '<b>DETAIL PESANAN:</b><br>Nama: jon<br>Metode Pembayaran: QRIS<br><br>Menu: Dada<br>Jumlah: 2<br>Catatan: -<br>', 'is_read' => 0, 'waktu' => '2026-07-23 20:10:36'],
            ['id' => 51, 'order_id' => 'DFC-230726-C796', 'pengirim' => 'konsumen', 'pesan' => '<b>DETAIL PESANAN:</b><br>Nama: Kharis <br>Metode Pembayaran: QRIS<br><br>Menu: Sayap (Wings)<br>Jumlah: 1<br>Catatan: Pedas<br>', 'is_read' => 0, 'waktu' => '2026-07-23 20:14:25'],
            ['id' => 52, 'order_id' => 'DFC-230726-C796', 'pengirim' => 'konsumen', 'pesan' => '<strong>Bukti Pembayaran QRIS:</strong><br><img src=\'https://dfc-cirebon.my.id/assets/img/bukti_bayar/bukti_DFC-230726-C796_6a62d8ac13934.jpg\' class=\'chat-img-zoomable\' style=\'width:100%; max-width:300px; border-radius:8px; margin-top:8px; cursor:pointer; border:1px solid #ddd;\' onclick=\'openLightbox(this.src)\' alt=\'Bukti Transfer\'>', 'is_read' => 0, 'waktu' => '2026-07-23 20:14:52'],
            ['id' => 53, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'konsumen', 'pesan' => '<b>DETAIL PESANAN:</b><br>Nama: damar<br>Metode Pembayaran: QRIS<br><br>Menu: Paha Bawah<br>Jumlah: 1<br>Catatan: -<br>', 'is_read' => 0, 'waktu' => '2026-07-23 20:22:34'],
            ['id' => 54, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'konsumen', 'pesan' => '<strong>Bukti Pembayaran QRIS:</strong><br><img src=\'https://dfc-cirebon.my.id/assets/img/bukti_bayar/bukti_DFC-230726-3F8B_6a62daa5f32b0.jpg\' class=\'chat-img-zoomable\' style=\'width:100%; max-width:300px; border-radius:8px; margin-top:8px; cursor:pointer; border:1px solid #ddd;\' onclick=\'openLightbox(this.src)\' alt=\'Bukti Transfer\'>', 'is_read' => 0, 'waktu' => '2026-07-23 20:23:18'],
            ['id' => 55, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'konsumen', 'pesan' => 'Sudah sampai mas?', 'is_read' => 0, 'waktu' => '2026-07-23 20:23:36'],
            ['id' => 56, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'outlet', 'pesan' => 'Yooo', 'is_read' => 1, 'waktu' => '2026-07-23 20:24:26'],
            ['id' => 57, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'konsumen', 'pesan' => 'p', 'is_read' => 0, 'waktu' => '2026-07-23 20:24:42'],
            ['id' => 58, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'outlet', 'pesan' => 'P', 'is_read' => 1, 'waktu' => '2026-07-23 20:24:48'],
            ['id' => 59, 'order_id' => 'DFC-230726-3F8B', 'pengirim' => 'konsumen', 'pesan' => 'p', 'is_read' => 0, 'waktu' => '2026-07-23 20:24:59'],
            ['id' => 60, 'order_id' => 'DFC-240726-4D6A', 'pengirim' => 'konsumen', 'pesan' => '<b>DETAIL PESANAN:</b><br>Nama: budi<br>Metode Pembayaran: QRIS<br><br>Menu: Dada<br>Jumlah: 1<br>Catatan: -<br><br>Menu: Nasi Putih<br>Jumlah: 1<br>Catatan: -<br>', 'is_read' => 0, 'waktu' => '2026-07-23 21:03:52'],
            ['id' => 61, 'order_id' => 'DFC-240726-4D6A', 'pengirim' => 'konsumen', 'pesan' => '<strong>Bukti Pembayaran QRIS:</strong><br><img src=\'https://dfc-cirebon.my.id/assets/img/bukti_bayar/bukti_DFC-240726-4D6A_6a62e447bfdcc.png\' class=\'chat-img-zoomable\' style=\'width:100%; max-width:300px; border-radius:8px; margin-top:8px; cursor:pointer; border:1px solid #ddd;\' onclick=\'openLightbox(this.src)\' alt=\'Bukti Transfer\'>', 'is_read' => 0, 'waktu' => '2026-07-23 21:04:24'],
            ['id' => 62, 'order_id' => 'DFC-240726-E958', 'pengirim' => 'konsumen', 'pesan' => '<b>DETAIL PESANAN:</b><br>Nama: Kharis<br>Metode Pembayaran: QRIS<br><br>Menu: Paha Bawah<br>Jumlah: 1<br>Catatan: -<br>', 'is_read' => 0, 'waktu' => '2026-07-23 21:17:44'],
            ['id' => 63, 'order_id' => 'DFC-240726-E958', 'pengirim' => 'konsumen', 'pesan' => '<strong>Bukti Pembayaran QRIS:</strong><br><img src=\'https://dfc-cirebon.my.id/assets/img/bukti_bayar/bukti_DFC-240726-E958_6a62e78f973a2.png\' class=\'chat-img-zoomable\' style=\'width:100%; max-width:300px; border-radius:8px; margin-top:8px; cursor:pointer; border:1px solid #ddd;\' onclick=\'openLightbox(this.src)\' alt=\'Bukti Transfer\'>', 'is_read' => 0, 'waktu' => '2026-07-23 21:18:24'],
            ['id' => 64, 'order_id' => 'DFC-240726-E958', 'pengirim' => 'konsumen', 'pesan' => 'Hai', 'is_read' => 0, 'waktu' => '2026-08-18 21:03:18'],
        ];
        foreach ($chat as $r) {
            DB::table('chat_pesanan')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // PENGAJUAN KEMITRAAN
        // =====================================================
        $pengajuan = [
            ['id' => 9, 'nama' => 'damar pasha wijaya', 'whatsapp' => '628996070813', 'lokasi_usaha' => 'Kabupaten Cirebon/Plered', 'pesan' => 'Halo saya ingin menjadi mitra, untuk proses nya nanti seperti apa kak?', 'status' => 'Approved', 'tanggal' => '2026-02-18 02:42:05'],
            ['id' => 10, 'nama' => 'Fajar', 'whatsapp' => '628123456789', 'lokasi_usaha' => 'Kabupaten Cirebon/Kecamatan Plered', 'pesan' => 'Halo saya ingin bergabung menjadi Mitra', 'status' => 'Pending', 'tanggal' => '2026-02-19 18:41:23'],
            ['id' => 11, 'nama' => 'Ayyas', 'whatsapp' => '6285329793332', 'lokasi_usaha' => 'Kedawung', 'pesan' => 'Tes', 'status' => 'Pending', 'tanggal' => '2026-07-23 02:03:06'],
            ['id' => 12, 'nama' => 'Kharis Destian Maulana', 'whatsapp' => '6281222453572', 'lokasi_usaha' => 'Kabupaten Cirebon', 'pesan' => '', 'status' => 'Approved', 'tanggal' => '2026-07-23 19:30:04'],
        ];
        foreach ($pengajuan as $r) {
            DB::table('pengajuan_mitra')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }

        // =====================================================
        // LOG AKTIVITAS SISTEM
        // =====================================================
        $log = [
            ['id' => 1, 'waktu' => '2025-12-24 13:09:25', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 2, 'waktu' => '2025-12-24 13:09:35', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 3, 'waktu' => '2025-12-24 13:09:47', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Mengubah stok \'Sayap (Wings)\' menjadi 10 pcs.'],
            ['id' => 4, 'waktu' => '2025-12-24 13:11:04', 'pelaku' => 'cipto', 'aksi' => 'Login Masuk', 'detail' => 'Admin cipto berhasil login ke sistem.'],
            ['id' => 5, 'waktu' => '2025-12-24 13:16:06', 'pelaku' => 'cipto', 'aksi' => 'Update Stok', 'detail' => 'Mengubah stok \'Paha Bawah\' menjadi 10 pcs.'],
            ['id' => 6, 'waktu' => '2025-12-24 14:21:59', 'pelaku' => 'cipto', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 7, 'waktu' => '2025-12-24 14:22:28', 'pelaku' => 'cipto', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 5 x Paha Bawah. Note: 3 original, 2 pedas'],
            ['id' => 8, 'waktu' => '2025-12-24 14:40:34', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 9, 'waktu' => '2025-12-24 14:40:51', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 2 x Sayap (Wings). Note: ori 1, pedes 1'],
            ['id' => 10, 'waktu' => '2025-12-24 19:46:13', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 0'],
            ['id' => 11, 'waktu' => '2025-12-24 19:46:18', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 12, 'waktu' => '2025-12-24 19:46:58', 'pelaku' => 'cipto', 'aksi' => 'Login Masuk', 'detail' => 'Admin cipto berhasil login ke sistem.'],
            ['id' => 13, 'waktu' => '2025-12-24 19:47:01', 'pelaku' => 'cipto', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 14, 'waktu' => '2025-12-24 19:47:24', 'pelaku' => 'klayan', 'aksi' => 'Login Masuk', 'detail' => 'Admin klayan berhasil login ke sistem.'],
            ['id' => 15, 'waktu' => '2025-12-24 19:53:51', 'pelaku' => 'sunyaragi', 'aksi' => 'Login Masuk', 'detail' => 'Admin sunyaragi berhasil login ke sistem.'],
            ['id' => 16, 'waktu' => '2025-12-24 19:53:54', 'pelaku' => 'sunyaragi', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 17, 'waktu' => '2025-12-24 19:54:36', 'pelaku' => 'pemuda', 'aksi' => 'Login Masuk', 'detail' => 'Admin pemuda berhasil login ke sistem.'],
            ['id' => 18, 'waktu' => '2025-12-24 19:54:39', 'pelaku' => 'pemuda', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 19, 'waktu' => '2025-12-24 19:55:18', 'pelaku' => 'palimanan', 'aksi' => 'Login Masuk', 'detail' => 'Admin palimanan berhasil login ke sistem.'],
            ['id' => 20, 'waktu' => '2025-12-24 19:55:36', 'pelaku' => 'palimanan', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 21, 'waktu' => '2025-12-24 19:56:12', 'pelaku' => 'jambe', 'aksi' => 'Login Masuk', 'detail' => 'Admin jambe berhasil login ke sistem.'],
            ['id' => 22, 'waktu' => '2025-12-24 19:56:16', 'pelaku' => 'jambe', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 23, 'waktu' => '2025-12-24 19:56:57', 'pelaku' => 'gegesik', 'aksi' => 'Login Masuk', 'detail' => 'Admin gegesik berhasil login ke sistem.'],
            ['id' => 24, 'waktu' => '2025-12-24 19:57:01', 'pelaku' => 'gegesik', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 25, 'waktu' => '2025-12-24 19:57:40', 'pelaku' => 'kaplongan', 'aksi' => 'Login Masuk', 'detail' => 'Admin kaplongan berhasil login ke sistem.'],
            ['id' => 26, 'waktu' => '2025-12-24 19:57:43', 'pelaku' => 'kaplongan', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 27, 'waktu' => '2025-12-24 19:58:28', 'pelaku' => 'tenajar', 'aksi' => 'Login Masuk', 'detail' => 'Admin tenajar berhasil login ke sistem.'],
            ['id' => 28, 'waktu' => '2025-12-24 19:58:31', 'pelaku' => 'tenajar', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 29, 'waktu' => '2025-12-24 19:59:32', 'pelaku' => 'pamengkang', 'aksi' => 'Login Masuk', 'detail' => 'Admin pamengkang berhasil login ke sistem.'],
            ['id' => 30, 'waktu' => '2025-12-24 19:59:36', 'pelaku' => 'pamengkang', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 31, 'waktu' => '2026-01-09 15:44:35', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 32, 'waktu' => '2026-01-09 15:44:42', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 33, 'waktu' => '2026-01-09 15:44:48', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 90'],
            ['id' => 34, 'waktu' => '2026-01-09 15:44:54', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Paha Bawah jadi 10'],
            ['id' => 35, 'waktu' => '2026-01-09 15:55:06', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 36, 'waktu' => '2026-01-09 15:55:18', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 0'],
            ['id' => 37, 'waktu' => '2026-01-09 15:55:25', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Paha Bawah jadi 0'],
            ['id' => 38, 'waktu' => '2026-01-09 15:55:30', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 39, 'waktu' => '2026-01-11 11:18:15', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 40, 'waktu' => '2026-01-12 20:05:11', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 41, 'waktu' => '2026-01-12 20:05:21', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 10'],
            ['id' => 42, 'waktu' => '2026-01-12 20:05:31', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Paha Bawah jadi 10'],
            ['id' => 43, 'waktu' => '2026-01-12 20:05:50', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 44, 'waktu' => '2026-01-16 12:22:33', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 45, 'waktu' => '2026-01-16 12:22:55', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 20'],
            ['id' => 46, 'waktu' => '2026-01-20 13:22:44', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 47, 'waktu' => '2026-01-20 13:23:00', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 48, 'waktu' => '2026-01-21 07:38:08', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 49, 'waktu' => '2026-01-21 09:07:33', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 50, 'waktu' => '2026-01-26 10:40:49', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 51, 'waktu' => '2026-02-18 17:48:10', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 52, 'waktu' => '2026-02-18 17:48:36', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Geprek jadi 10'],
            ['id' => 53, 'waktu' => '2026-02-18 17:48:47', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Dada jadi 10'],
            ['id' => 54, 'waktu' => '2026-02-18 17:49:04', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Paha Atas jadi 10'],
            ['id' => 55, 'waktu' => '2026-02-18 17:49:08', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Nasi Putih jadi 0'],
            ['id' => 56, 'waktu' => '2026-02-18 17:49:14', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Nasi Putih jadi 40'],
            ['id' => 57, 'waktu' => '2026-02-18 17:52:38', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 58, 'waktu' => '2026-02-18 17:59:57', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 59, 'waktu' => '2026-02-19 11:52:43', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 60, 'waktu' => '2026-02-19 12:09:28', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 61, 'waktu' => '2026-02-19 12:13:45', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi TUTUP'],
            ['id' => 62, 'waktu' => '2026-02-19 12:13:55', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Mengubah status toko menjadi BUKA'],
            ['id' => 63, 'waktu' => '2026-02-19 17:20:00', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 64, 'waktu' => '2026-02-20 06:25:17', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 5 x Sayap (Wings). Note: Pedas 2, Ori 3'],
            ['id' => 65, 'waktu' => '2026-02-20 08:53:35', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 66, 'waktu' => '2026-02-20 09:48:58', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 0'],
            ['id' => 67, 'waktu' => '2026-02-20 09:52:10', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 1 x Sayap (Wings). Note: Pedas'],
            ['id' => 68, 'waktu' => '2026-02-20 09:52:23', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Sayap (Wings) jadi 10'],
            ['id' => 69, 'waktu' => '2026-02-20 09:52:35', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 1 x Sayap (Wings). Note: Pedas'],
            ['id' => 70, 'waktu' => '2026-02-20 09:53:00', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual 1 x Nasi Putih. Note: Original'],
            ['id' => 71, 'waktu' => '2026-02-20 10:04:13', 'pelaku' => 'damar', 'aksi' => 'Login Masuk', 'detail' => 'Admin damar berhasil login ke sistem.'],
            ['id' => 72, 'waktu' => '2026-02-20 11:33:32', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 73, 'waktu' => '2026-03-11 11:54:46', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 74, 'waktu' => '2026-04-25 20:23:18', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 75, 'waktu' => '2026-07-13 11:25:56', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 76, 'waktu' => '2026-07-13 11:26:12', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Status toko diperbarui'],
            ['id' => 77, 'waktu' => '2026-07-13 11:28:06', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Es Doger Segar'],
            ['id' => 78, 'waktu' => '2026-07-13 11:35:46', 'pelaku' => 'sutawinangun', 'aksi' => 'Catat Penjualan', 'detail' => 'Menjual penjualan offline: Sayap (Wings)'],
            ['id' => 79, 'waktu' => '2026-07-13 11:41:02', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Status toko diperbarui'],
            ['id' => 80, 'waktu' => '2026-07-13 11:41:12', 'pelaku' => 'sutawinangun', 'aksi' => 'Update Stok', 'detail' => 'Ubah stok Burger'],
            ['id' => 81, 'waktu' => '2026-07-13 12:03:18', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Status toko diperbarui'],
            ['id' => 82, 'waktu' => '2026-07-13 12:03:22', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Status toko diperbarui'],
            ['id' => 83, 'waktu' => '2026-07-22 14:48:29', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 84, 'waktu' => '2026-07-22 14:48:33', 'pelaku' => 'sutawinangun', 'aksi' => 'Ubah Status Toko', 'detail' => 'Status toko diperbarui'],
            ['id' => 85, 'waktu' => '2026-07-22 19:38:58', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 86, 'waktu' => '2026-07-23 00:48:46', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
            ['id' => 87, 'waktu' => '2026-07-23 01:50:51', 'pelaku' => 'sutawinangun', 'aksi' => 'Login Masuk', 'detail' => 'Admin sutawinangun berhasil login ke sistem.'],
        ];
        foreach ($log as $r) {
            DB::table('log_aktivitas')->insert($r + ['created_at' => $sekarang, 'updated_at' => $sekarang]);
        }
    }
}
