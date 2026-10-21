<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;

/**
 * Controller Lokasi (halaman publik).
 * Menampilkan peta sebaran outlet dan daftar lokasi DFC.
 */
class LokasiController extends Controller
{
    /**
     * Menampilkan halaman peta lokasi.
     */
    public function index()
    {
        // Ambil seluruh lokasi dengan urutan kategori: Pusat, Outlet, Mitra.
        $lokasi = Lokasi::query()->urutKategori()->get();

        // Siapkan data peta, hanya lokasi yang punya koordinat lengkap.
        $dataLokasi = $lokasi
            ->filter(fn (Lokasi $item) => ! empty($item->latitude) && ! empty($item->longitude))
            ->map(fn (Lokasi $item) => [
                'category' => $item->kategori,
                'name' => $item->nama,
                'address' => $item->alamat,
                'lat' => $item->latitude,
                'lng' => $item->longitude,
            ])
            ->values()
            ->all();

        return view('publik.lokasi', [
            'judul' => 'Lokasi - DFC Cirebon',
            'lokasi' => $lokasi,
            'lokasiJson' => json_encode($dataLokasi),
        ]);
    }
}
