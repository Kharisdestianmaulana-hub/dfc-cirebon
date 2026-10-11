<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel chat_pesanan.
 *
 * Menyimpan percakapan antara konsumen dan outlet untuk setiap pesanan.
 * Satu pesanan dapat memiliki banyak pesan. Kolom pengirim menandai asal
 * pesan, yaitu konsumen, outlet, atau sistem.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_pesanan', function (Blueprint $table) {
            $table->id();
            // Nomor pesanan yang menjadi topik percakapan.
            $table->string('order_id', 50);
            // Asal pesan: konsumen, outlet, atau sistem.
            $table->enum('pengirim', ['konsumen', 'outlet', 'sistem']);
            // Isi pesan, boleh memuat HTML ringan seperti detail pesanan.
            $table->text('pesan');
            // Penanda apakah pesan sudah dibaca oleh pihak lawan bicara.
            $table->boolean('is_read')->default(false);
            // Waktu pesan dikirim.
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();

            // Percakapan selalu dicari berdasarkan nomor pesanan.
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_pesanan');
    }
};
