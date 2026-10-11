<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProfilPerusahaan.
 * Menyimpan konten halaman "Tentang Kami": sejarah, visi, dan misi.
 */
class ProfilPerusahaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'profil_perusahaan';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sejarah',
        'visi',
        'misi',
    ];

    /**
     * Ambil baris profil perusahaan, atau buat baris default bila belum ada.
     * Dipakai halaman publik Profil agar tidak pernah menampilkan data kosong.
     */
    public static function ambil(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'sejarah' => 'Dexpress Fried Chicken (DFC) adalah UMKM kuliner asli Cirebon yang menyajikan ayam goreng renyah dengan bumbu rahasia.',
                'visi' => 'Menjadi brand Fried Chicken lokal nomor 1 di Cirebon.',
                'misi' => 'Menyajikan produk ayam goreng yang Halal, Higienis, dan Hemat.',
            ]
        );
    }
}
