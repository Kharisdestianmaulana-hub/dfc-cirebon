<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel transaksi_pesanan.
 * Menyimpan seluruh pesanan konsumen dari website, termasuk rincian
 * item dalam bentuk JSON dan status pengerjaannya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pesanan', function (Blueprint $table) {
            $table->id();
            // Kode pesanan unik, contoh: DFC-111025-A1B2.
            $table->string('order_id', 50)->unique();
            // Nama pemesan.
            $table->string('nama_pemesan', 150);
            // Nomor HP pemesan, dipakai untuk pencarian riwayat.
            $table->string('no_hp', 30);
            // Kode outlet tujuan pesanan.
            $table->string('kode_outlet', 100)->nullable();
            // Total yang harus dibayar.
            $table->decimal('total_bayar', 15, 2)->default(0);
            // Metode pembayaran: Cash atau QRIS.
            $table->string('metode_bayar', 20)->default('Cash');
            // Rincian item pesanan dalam format JSON.
            $table->longText('detail_pesanan')->nullable();
            // Status: Menunggu Konfirmasi, Sedang Disiapkan, Siap Diambil, Selesai.
            $table->string('status_pesanan', 50)->default('Menunggu Konfirmasi');
            // Waktu pesanan dibuat.
            $table->timestamp('waktu_pesan')->useCurrent();
            $table->timestamps();

            $table->index('status_pesanan');
            $table->index('no_hp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pesanan');
    }
};
