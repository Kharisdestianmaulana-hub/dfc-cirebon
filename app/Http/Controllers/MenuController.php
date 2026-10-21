<?php

namespace App\Http\Controllers;

use App\Models\Menu;

/**
 * Controller Menu (halaman publik).
 * Menampilkan daftar seluruh menu jual DFC beserta harga.
 */
class MenuController extends Controller
{
    /**
     * Menampilkan daftar seluruh menu.
     */
    public function index()
    {
        // Ambil menu terbaru lebih dulu, mengikuti urutan sistem native.
        $menu = Menu::query()->orderByDesc('id')->get();

        return view('publik.menu', [
            'judul' => 'Daftar Menu - DFC Cirebon',
            'menu' => $menu,
        ]);
    }
}
