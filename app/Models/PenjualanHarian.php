<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PenjualanHarian.
 * Mencatat penjualan offline yang diinput karyawan outlet.
 */
class PenjualanHarian extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'penjualan_harian';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal',
        'waktu',
        'kode_outlet',
        'nama_menu',
        'qty',
        'catatan',
        'harga_satuan',
        'subtotal',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];
}
