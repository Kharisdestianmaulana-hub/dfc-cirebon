<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel lokasi.
 * Menyimpan data seluruh titik usaha DFC: Pusat, Outlet, Mitra, dan Cabang
 * beserta koordinat peta dan akun admin outlet yang terpasang.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            // Nama titik usaha, contoh: "Outlet Sutawinangun".
            $table->string('nama', 150);
            // Alamat lengkap titik usaha.
            $table->text('alamat')->nullable();
            // Kategori titik usaha menentukan tampilan badge dan peta.
            $table->enum('kategori', ['Pusat', 'Outlet', 'Mitra', 'Cabang'])->default('Outlet');
            // Koordinat peta, dibiarkan string agar mudah diisi manual dari Google Maps.
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            // Nomor telepon outlet yang dipakai untuk tautan WhatsApp konsumen.
            $table->string('telepon', 30)->nullable();
            // Username admin outlet. Kosong berarti outlet belum diaktifkan owner.
            $table->string('username_admin', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
