<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMitra;
use Illuminate\Http\Request;

/**
 * Controller Kemitraan (halaman publik).
 * Menampilkan form pendaftaran mitra dan memproses pengajuannya.
 */
class KemitraanController extends Controller
{
    /**
     * Menampilkan form pendaftaran mitra.
     */
    public function index()
    {
        return view('publik.kemitraan', [
            'judul' => 'Pendaftaran Mitra DFC Cirebon',
        ]);
    }

    /**
     * Memproses pengajuan kemitraan dari form publik.
     * Nomor WhatsApp yang diawali angka nol dinormalkan menjadi awalan 62.
     */
    public function kirim(Request $request)
    {
        // Validasi masukan wajib agar tidak ada data kosong yang tersimpan.
        $dataValid = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'lokasi' => ['required', 'string', 'max:255'],
            'pesan' => ['nullable', 'string'],
        ], [
            'nama.required' => 'Mohon lengkapi data pengajuan.',
            'whatsapp.required' => 'Mohon lengkapi data pengajuan.',
            'lokasi.required' => 'Mohon lengkapi data pengajuan.',
        ]);

        // Normalisasi nomor WhatsApp: 08xxx menjadi 628xxx.
        $whatsapp = trim($dataValid['whatsapp']);
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        }

        PengajuanMitra::query()->create([
            'nama' => trim($dataValid['nama']),
            'whatsapp' => $whatsapp,
            'lokasi_usaha' => trim($dataValid['lokasi']),
            'pesan' => trim((string) ($dataValid['pesan'] ?? '')),
        ]);

        return redirect()
            ->route('beranda')
            ->with('sukses', 'Pengajuan berhasil! Tim kami akan segera menghubungi Anda.');
    }
}
