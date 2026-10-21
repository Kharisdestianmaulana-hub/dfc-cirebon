<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel pengajuan_mitra.
 * Menyimpan pengajuan kemitraan yang dikirim calon mitra dari halaman publik.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_mitra', function (Blueprint $table) {
            $table->id();
            // Nama lengkap calon mitra.
            $table->string('nama', 150);
            // Nomor WhatsApp calon mitra, disimpan dengan awalan 62.
            $table->string('whatsapp', 30);
            // Rencana lokasi usaha.
            $table->string('lokasi_usaha', 255)->nullable();
            // Pesan tambahan dari calon mitra.
            $table->text('pesan')->nullable();
            // Waktu pengajuan masuk.
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_mitra');
    }
};
