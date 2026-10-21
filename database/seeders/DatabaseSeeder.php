<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder utama aplikasi DFC Cirebon.
 *
 * Memanggil DataAsliSeeder yang memuat seluruh data dari basis data produksi,
 * sehingga perintah php artisan migrate:fresh --seed menghasilkan aplikasi
 * dengan data yang sama seperti sistem yang sedang berjalan.
 *
 * Bila ingin menambahkan data contoh terpisah, cukup daftarkan seeder baru
 * pada array di bawah ini.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seluruh proses pengisian data awal.
     */
    public function run(): void
    {
        $this->call([
            DataAsliSeeder::class,
        ]);
    }
}
