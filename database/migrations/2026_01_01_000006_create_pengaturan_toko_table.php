<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel pengaturan_toko.
 * Menyimpan status buka/tutup setiap outlet beserta nama tampilannya.
 * Dipakai halaman Pesanan konsumen untuk menonaktifkan outlet yang tutup.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_toko', function (Blueprint $table) {
            $table->id();
            // Kode outlet, berisi username admin outlet.
            $table->string('kode_outlet', 100)->unique();
            // Nama outlet untuk ditampilkan.
            $table->string('nama_outlet', 150)->nullable();
            // Status operasional outlet.
            $table->enum('status', ['buka', 'tutup'])->default('tutup');
            // Nomor WhatsApp outlet untuk tautan pesan cepat konsumen.
            $table->string('no_hp', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_toko');
    }
};
