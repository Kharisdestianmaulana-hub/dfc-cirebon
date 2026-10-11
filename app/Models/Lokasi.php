<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Lokasi.
 * Mewakili setiap titik usaha DFC: Pusat, Outlet, Mitra, dan Cabang.
 * Model ini menjadi pusat data alamat, koordinat peta, dan akun admin outlet.
 */
class Lokasi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'lokasi';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'map_query',
        'kategori',
        'latitude',
        'longitude',
        'telepon',
        'username_admin',
        'mitra_username',
        'qris_image',
    ];

    /**
     * Relasi ke stok outlet: satu lokasi memiliki banyak baris stok menu.
     */
    public function stokOutlet(): HasMany
    {
        return $this->hasMany(StokOutlet::class, 'kode_outlet', 'username_admin');
    }

    /**
     * Relasi ke pengaturan toko berdasarkan kode outlet.
     */
    public function pengaturanToko(): HasMany
    {
        return $this->hasMany(PengaturanToko::class, 'kode_outlet', 'username_admin');
    }

    /**
     * Scope untuk mengambil hanya outlet yang sudah diaktifkan owner.
     */
    public function scopeAktif($query)
    {
        return $query->whereNotNull('username_admin')->where('username_admin', '!=', '');
    }

    /**
     * Scope untuk mengambil outlet yang belum diaktifkan owner.
     */
    public function scopeBelumAktif($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('username_admin')->orWhere('username_admin', '');
        });
    }

    /**
     * Urutan tampilan lokasi: Pusat, lalu Outlet, lalu Mitra, lalu sisanya.
     */
    public function scopeUrutKategori($query)
    {
        return $query->orderByRaw("kategori = 'Pusat' DESC")
            ->orderByRaw("kategori = 'Outlet' DESC")
            ->orderByRaw("kategori = 'Mitra' DESC")
            ->orderBy('nama');
    }
}
