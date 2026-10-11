<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel akun_mitra.
 * Menyimpan akun login portal mitra yang dibuat oleh owner.
 * Mitra hanya dapat melihat stok gudang (read only).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_mitra', function (Blueprint $table) {
            $table->id();
            // Nama mitra untuk ditampilkan di portal.
            $table->string('nama_mitra', 150);
            // Username login mitra, unik.
            $table->string('username', 100)->unique();
            // Password ter-hash (bcrypt).
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_mitra');
    }
};
