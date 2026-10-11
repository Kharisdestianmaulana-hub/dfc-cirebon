<?php

namespace App\Http\Controllers;

use App\Models\AdminGudang;
use App\Models\RiwayatStok;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller Gudang.
 * Mengurus login admin gudang pusat, pembaruan stok, dan riwayat perubahan stok.
 */
class GudangController extends Controller
{
    /**
     * Menampilkan halaman login admin gudang.
     */
    public function tampilLogin()
    {
        // Bila sudah login, langsung arahkan ke halaman stok.
        if (Auth::guard('gudang')->check()) {
            return redirect()->route('gudang.index');
        }

        return view('gudang.login', [
            'judul' => 'Login Gudang Pusat',
        ]);
    }

    /**
     * Memproses login admin gudang.
     */
    public function prosesLogin(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login memakai guard gudang.
        if (Auth::guard('gudang')->attempt($kredensial)) {
            $request->session()->regenerate();

            return redirect()->route('gudang.index');
        }

        return back()->withErrors([
            'login' => 'Username atau Password salah!',
        ])->withInput();
    }

    /**
     * Menampilkan daftar stok gudang pusat.
     */
    public function index()
    {
        return view('gudang.index', [
            'judul' => 'Gudang Pusat - Manajemen Stok',
            'stok' => StokGudang::query()->orderBy('nama_barang')->get(),
            'namaAdmin' => Auth::guard('gudang')->user()->username,
        ]);
    }

    /**
     * Memproses pembaruan stok dan mencatat perubahannya ke riwayat.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:stok_gudang,id'],
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $barang = StokGudang::query()->findOrFail($data['id']);
        $stokLama = (int) $barang->stok;
        $stokBaru = (int) $data['stok'];
        $selisih = $stokBaru - $stokLama;

        // Hanya proses bila jumlah stok benar-benar berubah.
        if ($selisih === 0) {
            return redirect()->route('gudang.index');
        }

        // Simpan perubahan stok dan catat riwayatnya dalam satu transaksi.
        DB::transaction(function () use ($barang, $stokBaru, $stokLama, $selisih) {
            $barang->update(['stok' => $stokBaru]);

            RiwayatStok::query()->create([
                'nama_barang' => $barang->nama_barang,
                'stok_lama' => $stokLama,
                'stok_baru' => $stokBaru,
                'selisih' => $selisih,
                'admin' => Auth::guard('gudang')->user()->username,
            ]);
        });

        return redirect()
            ->route('gudang.index')
            ->with('sukses', 'Sukses! Stok berhasil diupdate dan dicatat.');
    }

    /**
     * Menampilkan riwayat perubahan stok.
     */
    public function riwayat()
    {
        return view('gudang.riwayat', [
            'judul' => 'Riwayat Perubahan Stok Gudang',
            'riwayat' => RiwayatStok::query()->orderByDesc('waktu')->get(),
        ]);
    }

    /**
     * Memproses logout admin gudang.
     */
    public function logout(Request $request)
    {
        Auth::guard('gudang')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('gudang.login');
    }
}
