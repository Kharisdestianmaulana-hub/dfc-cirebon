<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel request_stok.
 * Menyimpan permintaan restock bahan baku dari outlet ke gudang pusat,
 * beserta status persetujuan owner.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_stok', function (Blueprint $table) {
            $table->id();
            // Nama outlet pengaju permintaan.
            $table->string('nama_outlet', 150);
            // Nama barang yang diminta.
            $table->string('nama_barang', 150);
            // Jumlah yang diminta.
            $table->integer('jumlah')->default(1);
            // Satuan barang diminta.
            $table->string('satuan', 30)->nullable();
            // Status: Pending, Disetujui, Ditolak.
            $table->string('status', 30)->default('Pending');
            // Alasan bila permintaan ditolak.
            $table->string('alasan_tolak', 255)->nullable();
            // Waktu permintaan dikirim.
            $table->timestamp('waktu_request')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_stok');
    }
};
