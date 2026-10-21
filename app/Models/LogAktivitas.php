<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model LogAktivitas.
 * Menyimpan jejak aktivitas karyawan outlet untuk halaman log pada panel owner.
 */
class LogAktivitas extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'log_aktivitas';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'waktu',
        'pelaku',
        'aksi',
        'detail',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Helper statis untuk mencatat satu baris log aktivitas.
     * Dipakai controller outlet agar pencatatan log konsisten di satu tempat.
     */
    public static function catat(string $pelaku, string $aksi, string $detail): void
    {
        static::create([
            'waktu' => now(),
            'pelaku' => $pelaku,
            'aksi' => $aksi,
            'detail' => $detail,
        ]);
    }
}
