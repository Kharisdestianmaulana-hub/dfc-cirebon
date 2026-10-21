<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel stok_outlet.
 * Menyimpan stok setiap menu untuk setiap outlet.
 * Pada sistem native, stok ini disimpan sebagai kolom dinamis stok_<username>
 * di tabel menu. Di Laravel dinormalisasi ke tabel ini agar rapi dan aman.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_outlet', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel menu.
            $table->foreignId('menu_id')->constrained('menu')->cascadeOnDelete();
            // Kode outlet, berisi username admin outlet pemilik stok.
            $table->string('kode_outlet', 100);
            // Jumlah stok menu tersebut di outlet terkait.
            $table->integer('stok')->default(0);
            $table->timestamps();

            // Satu menu hanya boleh punya satu baris stok per outlet.
            $table->unique(['menu_id', 'kode_outlet']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_outlet');
    }
};
