<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel log_aktivitas.
 * Menyimpan jejak aktivitas karyawan outlet, dipakai pada halaman
 * "Log Aktivitas Karyawan" di panel owner.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            // Waktu aktivitas terjadi.
            $table->dateTime('waktu')->nullable();
            // Pelaku aktivitas, umumnya kode outlet.
            $table->string('pelaku', 100)->nullable();
            // Jenis aksi, contoh: "Catat Penjualan", "Ubah Status Toko".
            $table->string('aksi', 100)->nullable();
            // Keterangan rinci aktivitas.
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
