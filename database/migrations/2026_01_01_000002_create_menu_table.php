<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel menu.
 * Menyimpan daftar menu jual DFC beserta harga dan nama file gambar.
 * Stok per outlet TIDAK disimpan di sini (lihat tabel stok_outlet) agar
 * mengikuti kaidah normalisasi basis data yang benar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            // Nama menu, contoh: "Geprek Original".
            $table->string('nama', 150);
            // Harga jual dalam rupiah penuh (tanpa desimal).
            $table->integer('harga')->default(0);
            // Nama file gambar di folder public/assets/img/owner.
            $table->string('gambar', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
