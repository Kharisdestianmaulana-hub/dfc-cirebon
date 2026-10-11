<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Model AdminOutlet.
 * Akun karyawan (kasir) outlet yang dibuat owner saat aktivasi outlet.
 * Memakai Authenticatable agar dapat dipakai guard login Laravel.
 */
class AdminOutlet extends Authenticatable
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'nama_outlet',
        'kode_outlet',
        'nomor_wa',
    ];

    /**
     * Kolom yang disembunyikan saat model diubah menjadi array atau JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Relasi ke data lokasi outlet milik akun ini.
     * Dihubungkan lewat kolom username_admin pada tabel lokasi.
     */
    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'username_admin', 'username');
    }
}
