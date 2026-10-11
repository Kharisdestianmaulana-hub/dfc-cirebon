<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RequestStok.
 * Menyimpan permintaan restock bahan baku dari outlet ke gudang pusat.
 */
class RequestStok extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'request_stok';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_outlet',
        'nama_barang',
        'jumlah',
        'satuan',
        'status',
        'alasan_tolak',
        'waktu_request',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_request' => 'datetime',
    ];

    /**
     * Daftar status permintaan yang sah.
     *
     * @var array<int, string>
     */
    public const STATUS = ['Pending', 'Disetujui', 'Ditolak'];

    /**
     * Scope untuk permintaan yang masih menunggu keputusan owner.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
}
