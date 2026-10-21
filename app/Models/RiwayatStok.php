<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RiwayatStok.
 * Menyimpan jejak audit setiap perubahan stok gudang pusat.
 */
class RiwayatStok extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'riwayat_stok';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mitra_username',
        'nama_barang',
        'stok_lama',
        'stok_baru',
        'selisih',
        'admin',
        'waktu',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu' => 'datetime',
    ];
}
