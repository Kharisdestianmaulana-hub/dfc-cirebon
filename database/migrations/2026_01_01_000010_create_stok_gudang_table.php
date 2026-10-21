<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel stok_gudang.
 * Menyimpan master stok bahan baku pada gudang pusat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_gudang', function (Blueprint $table) {
            $table->id();
            // Nama barang, contoh: "Tepung Bumbu".
            $table->string('nama_barang', 150);
            // Jumlah stok tersedia.
            $table->integer('stok')->default(0);
            // Satuan barang, contoh: Pcs, Kg, Liter.
            $table->string('satuan', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_gudang');
    }
};
