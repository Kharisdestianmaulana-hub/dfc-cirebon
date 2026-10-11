<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel laporan_keuangan.
 * Menyimpan laporan omzet harian setiap outlet, beserta pengeluaran
 * dan omzet bersih hasil pengurangan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id();
            // Tanggal laporan.
            $table->date('tanggal');
            // Kode outlet penyetor laporan.
            $table->string('kode_outlet', 100);
            // Omzet kotor sebelum dipotong pengeluaran.
            $table->decimal('omzet_kotor', 15, 2)->default(0);
            // Total pengeluaran operasional hari itu.
            $table->decimal('pengeluaran', 15, 2)->default(0);
            // Omzet bersih hasil omzet kotor dikurangi pengeluaran.
            $table->decimal('omzet_bersih', 15, 2)->default(0);
            // Catatan opsional dari karyawan outlet.
            $table->text('catatan')->nullable();
            // Jam laporan diinput.
            $table->time('waktu_input')->nullable();
            $table->timestamps();

            // Satu outlet hanya boleh menyetor satu laporan per tanggal.
            $table->unique(['tanggal', 'kode_outlet']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};
