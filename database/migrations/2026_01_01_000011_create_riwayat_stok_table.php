<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel riwayat_stok.
 * Menyimpan jejak audit setiap perubahan stok gudang pusat,
 * baik yang dilakukan owner maupun admin gudang.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            // Nama barang yang berubah stoknya.
            $table->string('nama_barang', 150);
            // Stok sebelum perubahan.
            $table->integer('stok_lama')->default(0);
            // Stok setelah perubahan.
            $table->integer('stok_baru')->default(0);
            // Selisih perubahan, bisa positif atau negatif.
            $table->integer('selisih')->default(0);
            // Nama admin yang melakukan perubahan.
            $table->string('admin', 100)->nullable();
            // Waktu perubahan tercatat.
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
