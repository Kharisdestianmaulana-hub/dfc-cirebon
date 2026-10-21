<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model LaporanKeuangan.
 * Menyimpan laporan omzet harian tiap outlet yang disetor karyawan.
 */
class LaporanKeuangan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'laporan_keuangan';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal',
        'kode_outlet',
        'omzet_kotor',
        'pengeluaran',
        'omzet_bersih',
        'catatan',
        'waktu_input',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'omzet_kotor' => 'decimal:2',
        'pengeluaran' => 'decimal:2',
        'omzet_bersih' => 'decimal:2',
    ];

    /**
     * Scope untuk menyaring laporan berdasarkan bulan dan tahun.
     */
    public function scopePeriode($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }
}
