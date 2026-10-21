<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PengaturanToko.
 * Menyimpan status buka atau tutup setiap outlet, dipakai halaman Pesanan
 * untuk menonaktifkan outlet yang sedang tutup.
 */
class PengaturanToko extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'pengaturan_toko';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_outlet',
        'nama_outlet',
        'status',
        'no_hp',
    ];

    /**
     * Scope untuk mengambil hanya outlet yang berstatus buka.
     */
    public function scopeBuka($query)
    {
        return $query->where('status', 'buka');
    }

    /**
     * Peta status toko dengan kode_outlet sebagai kunci.
     * Dipakai halaman pesanan konsumen untuk memeriksa status seluruh outlet.
     *
     * @return array<string, string>
     */
    public static function petaStatus(): array
    {
        return static::query()->pluck('status', 'kode_outlet')->toArray();
    }
}
