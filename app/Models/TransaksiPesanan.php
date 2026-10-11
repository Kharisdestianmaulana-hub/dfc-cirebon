<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TransaksiPesanan.
 * Mewakili pesanan konsumen dari website, termasuk rincian item dalam JSON
 * dan status pengerjaan yang diperbarui karyawan outlet.
 */
class TransaksiPesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'transaksi_pesanan';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'nama_pemesan',
        'no_hp',
        'kode_outlet',
        'total_bayar',
        'metode_bayar',
        'detail_pesanan',
        'status_pesanan',
        'waktu_pesan',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_pesan' => 'datetime',
        'total_bayar' => 'decimal:2',
    ];

    /**
     * Daftar status pesanan yang sah, dipakai untuk validasi perubahan status.
     *
     * @var array<int, string>
     */
    public const STATUS = [
        'Menunggu Konfirmasi',
        'Sedang Disiapkan',
        'Siap Diambil',
        'Selesai',
    ];

    /**
     * Ambil rincian item pesanan hasil decode kolom JSON.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getItemsAttribute(): array
    {
        $data = json_decode((string) $this->detail_pesanan, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Scope untuk menyaring pesanan berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status_pesanan', $status);
    }

    /**
     * Scope untuk pesanan yang belum selesai.
     */
    public function scopeBelumSelesai($query)
    {
        return $query->where('status_pesanan', '!=', 'Selesai');
    }
}
