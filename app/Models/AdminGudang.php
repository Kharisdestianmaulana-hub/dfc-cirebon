<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Model AdminGudang.
 * Akun tunggal admin gudang pusat. Kredensialnya diatur owner.
 * Memakai Authenticatable agar dapat dipakai guard login Laravel.
 */
class AdminGudang extends Authenticatable
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'admin_gudang';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
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
