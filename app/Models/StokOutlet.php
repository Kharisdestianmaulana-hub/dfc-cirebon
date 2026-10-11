<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model StokOutlet.
 * Menyimpan jumlah stok sebuah menu pada satu outlet tertentu.
 * Menggantikan kolom dinamis stok_<username> pada versi native lama.
 */
class StokOutlet extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'stok_outlet';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'menu_id',
        'kode_outlet',
        'stok',
    ];

    /**
     * Relasi ke menu pemilik stok ini.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
