<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration penyesuaian kolom dari basis data asli DFC Cirebon.
 *
 * Basis data asli yang dipakai di peladen produksi memuat beberapa kolom yang
 * belum ada pada migrasi awal, misalnya kolom pendukung peta, gambar QRIS,
 * kategori barang gudang, dan status pengajuan mitra. Migrasi ini menambahkan
 * kolom-kolom tersebut agar seluruh data asli dapat dipindahkan tanpa ada yang
 * hilang.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tabel admin: kolom keterangan outlet dan nomor WhatsApp penanggung jawab.
        Schema::table('admin', function (Blueprint $table) {
            $table->string('nama_outlet', 100)->nullable()->after('password');
            $table->string('kode_outlet', 50)->nullable()->after('nama_outlet');
            $table->string('nomor_wa', 20)->nullable()->after('kode_outlet');
        });

        // Tabel admin_gudang: nama lengkap petugas gudang.
        Schema::table('admin_gudang', function (Blueprint $table) {
            $table->string('nama_lengkap', 100)->nullable()->after('password');
        });

        // Tabel lokasi: teks pencarian peta, pemilik mitra, dan gambar QRIS outlet.
        Schema::table('lokasi', function (Blueprint $table) {
            $table->text('map_query')->nullable()->after('alamat');
            $table->string('mitra_username', 50)->nullable()->after('username_admin');
            $table->string('qris_image', 255)->nullable()->after('mitra_username');
        });

        // Tabel menu: kategori menu dan penanda pemilik menu.
        Schema::table('menu', function (Blueprint $table) {
            $table->string('kategori', 50)->nullable()->after('gambar');
            $table->string('pemilik_menu', 50)->default('pusat')->after('kategori');
        });

        // Tabel pengajuan_mitra: status tindak lanjut pengajuan.
        Schema::table('pengajuan_mitra', function (Blueprint $table) {
            $table->string('status', 20)->default('Pending')->after('pesan');
        });

        // Tabel riwayat_stok: penanda mitra bila perubahan berasal dari mitra.
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->string('mitra_username', 100)->nullable()->after('id');
        });

        // Tabel stok_gudang: penanda mitra dan kategori barang gudang.
        Schema::table('stok_gudang', function (Blueprint $table) {
            $table->string('mitra_username', 100)->nullable()->after('id');
            $table->string('kategori', 50)->nullable()->after('mitra_username');
        });
    }

    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn(['nama_outlet', 'kode_outlet', 'nomor_wa']);
        });

        Schema::table('admin_gudang', function (Blueprint $table) {
            $table->dropColumn('nama_lengkap');
        });

        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn(['map_query', 'mitra_username', 'qris_image']);
        });

        Schema::table('menu', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'pemilik_menu']);
        });

        Schema::table('pengajuan_mitra', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropColumn('mitra_username');
        });

        Schema::table('stok_gudang', function (Blueprint $table) {
            $table->dropColumn(['mitra_username', 'kategori']);
        });
    }
};
