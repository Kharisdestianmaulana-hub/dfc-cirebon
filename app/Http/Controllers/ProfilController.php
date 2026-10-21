<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;

/**
 * Controller Profil (halaman publik).
 * Menampilkan halaman "Tentang Kami" DFC Cirebon.
 */
class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil perusahaan.
     */
    public function index()
    {
        return view('publik.profil', [
            'judul' => 'Tentang Kami - DFC Cirebon',
            'profil' => ProfilPerusahaan::ambil(),
        ]);
    }
}
