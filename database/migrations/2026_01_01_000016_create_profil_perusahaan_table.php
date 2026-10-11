<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel profil_perusahaan.
 * Menyimpan konten halaman "Tentang Kami" (sejarah, visi, misi)
 * yang dapat diubah owner dari panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_perusahaan', function (Blueprint $table) {
            $table->id();
            // Cerita sejarah perusahaan.
            $table->text('sejarah')->nullable();
            // Visi perusahaan.
            $table->text('visi')->nullable();
            // Misi perusahaan.
            $table->text('misi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaan');
    }
};
