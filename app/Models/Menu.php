<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Menu.
 * Mewakili satu jenis menu jual DFC beserta harga dan gambar tampilannya.
 */
class Menu extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'harga',
        'gambar',
        'kategori',
        'pemilik_menu',
    ];

    /**
     * Relasi ke stok outlet: satu menu memiliki stok pada tiap outlet.
     */
    public function stokOutlet(): HasMany
    {
        return $this->hasMany(StokOutlet::class, 'menu_id');
    }

    /**
     * Accessor untuk membuat URL gambar menu yang siap dipakai di view.
     * Bila gambar kosong, kembalikan null agar view dapat menampilkan placeholder.
     */
    public function getUrlGambarAttribute(): ?string
    {
        if (empty($this->gambar)) {
            return null;
        }

        return asset('assets/img/owner/' . basename($this->gambar));
    }

    /**
     * Accessor harga dalam format rupiah, contoh: "Rp 15.000".
     */
    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->harga, 0, ',', '.');
    }
}
