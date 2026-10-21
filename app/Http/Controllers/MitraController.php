<?php

namespace App\Http\Controllers;

use App\Models\AkunMitra;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller Mitra.
 * Mengurus login mitra dan halaman pemantauan stok gudang (hanya lihat).
 */
class MitraController extends Controller
{
    /**
     * Menampilkan halaman login mitra.
     */
    public function tampilLogin()
    {
        // Bila sudah login, langsung arahkan ke halaman stok.
        if (Auth::guard('mitra')->check()) {
            return redirect()->route('mitra.index');
        }

        return view('mitra.login', [
            'judul' => 'Login Mitra DFC Cirebon',
        ]);
    }

    /**
     * Memproses login mitra.
     */
    public function prosesLogin(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login memakai guard mitra.
        if (Auth::guard('mitra')->attempt($kredensial)) {
            $request->session()->regenerate();

            return redirect()->route('mitra.index');
        }

        return back()->withErrors([
            'login' => 'Username atau Password salah!',
        ])->withInput();
    }

    /**
     * Menampilkan halaman info stok gudang untuk mitra.
     */
    public function index()
    {
        return view('mitra.index', [
            'judul' => 'Info Stok Gudang - Mitra DFC',
            'daftarStok' => StokGudang::query()->orderBy('nama_barang')->get(),
            'namaMitra' => Auth::guard('mitra')->user()->nama_mitra,
            'waAdmin' => (string) config('dfc.wa_admin'),
        ]);
    }

    /**
     * Endpoint JSON stok ringkas, dipanggil berkala oleh halaman mitra
     * untuk menampilkan pembaruan stok secara langsung.
     */
    public function cekStok()
    {
        // Ambil hanya id dan stok agar respons ringan.
        $data = StokGudang::query()->get(['id', 'stok']);

        return response()->json($data);
    }

    /**
     * Memproses logout mitra.
     */
    public function logout(Request $request)
    {
        Auth::guard('mitra')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mitra.login');
    }
}
