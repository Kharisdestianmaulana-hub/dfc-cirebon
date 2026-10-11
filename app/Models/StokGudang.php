<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StokGudang.
 * Menyimpan master stok bahan baku pada gudang pusat.
 */
class StokGudang extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'stok_gudang';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mitra_username',
        'kategori',
        'nama_barang',
        'stok',
        'satuan',
    ];

    /**
     * Batas stok dianggap kritis, dipakai untuk badge status dan dashboard.
     */
    public const BATAS_KRITIS = 10;

    /**
     * Scope untuk menyaring barang dengan stok kritis.
     */
    public function scopeKritis($query)
    {
        return $query->where('stok', '<', self::BATAS_KRITIS);
    }

    /**
     * Accessor penanda apakah stok barang ini kritis.
     */
    public function getKritisAttribute(): bool
    {
        return (int) $this->stok < self::BATAS_KRITIS;
    }
}
