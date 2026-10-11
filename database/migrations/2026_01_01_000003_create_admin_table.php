<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel admin.
 * Menyimpan akun karyawan outlet (kasir) yang dibuat owner saat aktivasi outlet.
 * Password disimpan dalam bentuk hash agar aman.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            // Username login karyawan outlet, unik di seluruh sistem.
            $table->string('username', 100)->unique();
            // Password ter-hash (bcrypt).
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
