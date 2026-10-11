<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel admin_gudang.
 * Menyimpan satu akun admin gudang pusat yang kredensialnya diatur oleh owner.
 * Menggantikan akun plain text pada sistem native lama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_gudang', function (Blueprint $table) {
            $table->id();
            // Username login admin gudang pusat.
            $table->string('username', 100);
            // Password ter-hash (bcrypt).
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_gudang');
    }
};
