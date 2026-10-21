<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Model AkunMitra.
 * Menyimpan akun login portal mitra yang dibuat owner.
 * Mitra hanya memiliki akses lihat stok gudang (read only).
 * Memakai Authenticatable agar dapat dipakai guard login mitra.
 */
class AkunMitra extends Authenticatable
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'akun_mitra';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_mitra',
        'username',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat model diubah menjadi array atau JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
}
