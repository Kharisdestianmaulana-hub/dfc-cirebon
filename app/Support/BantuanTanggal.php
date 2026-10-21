<?php

namespace App\Support;

/**
 * Kelas BantuanTanggal.
 * Menyediakan format tanggal dan bulan dalam bahasa Indonesia
 * agar seluruh tampilan aplikasi konsisten berbahasa Indonesia.
 */
class BantuanTanggal
{
    /**
     * Daftar nama bulan dalam bahasa Indonesia, diindeks dari 01 sampai 12.
     *
     * @var array<string, string>
     */
    public const BULAN = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    /**
     * Format tanggal lengkap bahasa Indonesia, contoh: "11 Oktober 2026".
     */
    public static function tanggalLengkap(?\DateTimeInterface $tanggal = null): string
    {
        $tanggal = $tanggal ?: now();

        $bulanKe = $tanggal->format('m');

        return $tanggal->format('d') . ' ' . (self::BULAN[$bulanKe] ?? $bulanKe) . ' ' . $tanggal->format('Y');
    }

    /**
     * Ubah format tanggal singkat bahasa Indonesia, contoh: 11 Okt 2026.
     */
    public static function tanggalSingkat(?\DateTimeInterface $tanggal = null): string
    {
        $tanggal = $tanggal ?: now();

        $namaBulan = substr(self::BULAN[$tanggal->format('m')] ?? '', 0, 3);

        return $tanggal->format('d') . ' ' . $namaBulan;
    }
}
