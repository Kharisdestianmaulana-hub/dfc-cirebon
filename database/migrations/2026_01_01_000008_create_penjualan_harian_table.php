<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel penjualan_harian.
 * Mencatat penjualan offline (tatap muka) yang diinput karyawan outlet.
 * Setiap baris mengurangi stok outlet terkait.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_harian', function (Blueprint $table) {
            $table->id();
            // Tanggal penjualan terjadi.
            $table->date('tanggal');
            // Jam penjualan terjadi.
            $table->time('waktu');
            // Kode outlet yang mencatat penjualan.
            $table->string('kode_outlet', 100);
            // Nama menu yang terjual.
            $table->string('nama_menu', 150);
            // Jumlah porsi terjual.
            $table->integer('qty')->default(1);
            // Catatan rasa atau detail tambahan dari karyawan.
            $table->string('catatan', 255)->nullable();
            // Harga satuan saat transaksi.
            $table->decimal('harga_satuan', 15, 2)->default(0);
            // Subtotal hasil qty dikali harga satuan.
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();

            $table->index('tanggal');
            $table->index('kode_outlet');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_harian');
    }
};
