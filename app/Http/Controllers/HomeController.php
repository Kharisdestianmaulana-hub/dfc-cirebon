<?php

namespace App\Http\Controllers;

/**
 * Controller Home (Beranda).
 * Menampilkan halaman utama publik DFC Cirebon.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda.
     */
    public function index()
    {
        return view('publik.home', [
            'judul' => 'Beranda - DFC Cirebon',
        ]);
    }
}
