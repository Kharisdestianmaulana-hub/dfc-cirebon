<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ChatPesanan.
 *
 * Mewakili satu pesan dalam percakapan pesanan antara konsumen dan outlet.
 * Relasi ke TransaksiPesanan dihubungkan melalui kolom order_id, bukan id
 * bawaan, karena nomor pesanan itulah yang menjadi topik percakapan.
 */
class ChatPesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'chat_pesanan';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'pengirim',
        'pesan',
        'is_read',
        'waktu',
    ];

    /**
     * Konversi tipe kolom secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'waktu' => 'datetime',
    ];

    /**
     * Relasi ke pesanan induk.
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(TransaksiPesanan::class, 'order_id', 'order_id');
    }

    /**
     * Ruang lingkup pesan yang belum dibaca oleh pihak lawan bicara.
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Accessor isi pesan yang sudah dibersihkan untuk ditampilkan di halaman.
     *
     * Sebagian pesan dibuat otomatis oleh sistem dan memuat HTML ringan,
     * misalnya rincian pesanan dan tautan bukti pembayaran. Accessor ini
     * mempertahankan tag aman saja, lalu membuang atribut penanganan peristiwa
     * dan skema URL berbahaya agar halaman tetap aman.
     */
    public function getPesanAmanAttribute(): string
    {
        $tagDiizinkan = '<b><strong><i><em><u><br><p><span><img>';

        $teks = strip_tags((string) $this->pesan, $tagDiizinkan);

        // Buang atribut seperti onerror, onclick, dan sejenisnya.
        $teks = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $teks);

        // Buang skema URL yang dapat menjalankan kode.
        $teks = preg_replace('/\s*javascript\s*:/i', '', $teks);

        return $teks;
    }

    /**
     * Accessor penanda apakah pesan berasal dari konsumen.
     */
    public function getDariKonsumenAttribute(): bool
    {
        return $this->pengirim === 'konsumen';
    }
}
