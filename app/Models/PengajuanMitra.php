<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PengajuanMitra.
 * Menyimpan pengajuan kemitraan dari halaman publik form kemitraan.
 */
class PengajuanMitra extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'pengajuan_mitra';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'whatsapp',
        'lokasi_usaha',
        'pesan',
        'status',
        'tanggal',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'datetime',
    ];
}
