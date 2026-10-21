<?php

namespace App\Http\Controllers;

use App\Models\AdminGudang;
use App\Models\AdminOutlet;
use App\Models\AkunMitra;
use App\Models\LaporanKeuangan;
use App\Models\LogAktivitas;
use App\Models\Lokasi;
use App\Models\Menu;
use App\Models\PengajuanMitra;
use App\Models\PenjualanHarian;
use App\Models\ProfilPerusahaan;
use App\Models\RequestStok;
use App\Models\RiwayatStok;
use App\Models\StokGudang;
use App\Models\TransaksiPesanan;
use App\Services\LayananOwner;
use App\Support\BantuanTanggal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Controller Owner.
 * Panel kendali utama yang menggabungkan seluruh modul manajemen:
 * dashboard, kelola menu, kelola lokasi, aktivasi outlet, stok gudang,
 * permintaan stok, laporan, riwayat penjualan, log, profil, kemitraan, dan akun mitra.
 */
class OwnerController extends Controller
{
    public function __construct(private readonly LayananOwner $layananOwner)
    {
    }

    // =================================================================
    // AUTENTIKASI OWNER
    // =================================================================

    /**
     * Menampilkan halaman login owner.
     */
    public function tampilLogin(Request $request)
    {
        // Bila sudah login, langsung arahkan ke dashboard.
        if ($request->session()->get('owner_login', false)) {
            return redirect()->route('owner.dashboard');
        }

        return view('owner.login', [
            'judul' => 'Login Owner DFC Cirebon',
        ]);
    }

    /**
     * Memproses login owner.
     */
    public function prosesLogin(Request $request)
    {
        $data = $request->validate([
            'user' => ['required', 'string'],
            'pass' => ['required', 'string'],
        ]);

        // Periksa kredensial melalui layanan owner.
        if ($this->layananOwner->kredensialValid($data['user'], $data['pass'])) {
            $request->session()->regenerate();
            $request->session()->put('owner_login', true);
            $request->session()->put('owner_username', $data['user']);

            return redirect()->route('owner.dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password owner salah.',
        ]);
    }

    /**
     * Memproses logout owner.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['owner_login', 'owner_username']);

        return redirect()->route('owner.login');
    }

    // =================================================================
    // DASHBOARD
    // =================================================================

    /**
     * Menampilkan dashboard owner beserta ringkasan bisnis hari ini.
     */
    public function dashboard()
    {
        $hariIni = now()->toDateString();

        // Ambil seluruh data ringkasan dalam satu kumpulan query.
        $totalOmzet = (float) LaporanKeuangan::query()->where('tanggal', $hariIni)->sum('omzet_bersih');
        $jumlahKritis = StokGudang::query()->kritis()->count();
        $totalItemTerjual = (int) PenjualanHarian::query()->where('tanggal', $hariIni)->sum('qty');
        $outletBuka = \App\Models\PengaturanToko::query()->where('status', 'buka')->count();
        $requestPending = RequestStok::query()->pending()->count();

        // Data chart omzet tujuh hari terakhir.
        $labelsChart = [];
        $valuesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $labelsChart[] = BantuanTanggal::tanggalSingkat($tanggal);
            $valuesChart[] = (float) LaporanKeuangan::query()
                ->where('tanggal', $tanggal->toDateString())
                ->sum('omzet_bersih');
        }

        return view('owner.dashboard', [
            'judul' => 'Dashboard Owner - DFC Cirebon',
            'active' => 'dashboard',
            'tanggalLengkap' => BantuanTanggal::tanggalLengkap(),
            'totalOmzet' => $totalOmzet,
            'kartuOmzet' => $this->kelasKartuOmzet($totalOmzet),
            'jumlahKritis' => $jumlahKritis,
            'totalItemTerjual' => $totalItemTerjual,
            'outletBuka' => $outletBuka,
            'requestPending' => $requestPending,
            'chartLabels' => $labelsChart,
            'chartValues' => $valuesChart,
            'timeline' => $this->susunTimeline($hariIni),
            'laporanTerbaru' => LaporanKeuangan::query()->orderByDesc('id')->limit(5)->get(),
            'menuTerlaris' => PenjualanHarian::query()
                ->select('nama_menu', DB::raw('SUM(qty) as total_terjual'))
                ->where('tanggal', $hariIni)
                ->groupBy('nama_menu')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get(),
        ]);
    }

    /**
     * Menentukan kelas warna kartu omzet berdasarkan nilainya.
     */
    private function kelasKartuOmzet(float $total): string
    {
        if ($total > 0 && $total < 200000) {
            return 'omzet-biru';
        }
        if ($total >= 200000 && $total < 500000) {
            return 'omzet-hijau';
        }
        if ($total >= 500000 && $total < 1000000) {
            return 'omzet-oranye';
        }
        if ($total >= 1000000) {
            return 'omzet-merah';
        }

        return 'omzet-putih';
    }

    /**
     * Menyusun linimasa aktivitas terkini dari penjualan, permintaan stok,
     * dan laporan keuangan hari ini.
     *
     * @return array<int, array<string, string>>
     */
    private function susunTimeline(string $hariIni): array
    {
        $timeline = [];

        // Aktivitas penjualan hari ini.
        foreach (PenjualanHarian::query()->where('tanggal', $hariIni)->orderByDesc('waktu')->limit(5)->get() as $row) {
            $timeline[] = [
                'waktu' => now()->setTimeFromTimeString((string) $row->waktu)->toDateTimeString(),
                'outlet' => ucfirst((string) $row->kode_outlet),
                'teks' => 'Menjual <b>' . e($row->nama_menu) . '</b> senilai <b style="color:#2e7d32;">Rp ' . number_format((float) $row->subtotal, 0, ',', '.') . '</b>',
                'icon' => 'ph-fill ph-shopping-cart',
                'color' => '#2e7d32',
                'bg' => '#e8f5e9',
            ];
        }

        // Aktivitas permintaan stok hari ini.
        foreach (RequestStok::query()->orderByDesc('waktu_request')->limit(5)->get() as $row) {
            if (optional($row->waktu_request)->toDateString() !== $hariIni) {
                continue;
            }
            $timeline[] = [
                'waktu' => $row->waktu_request->toDateTimeString(),
                'outlet' => (string) $row->nama_outlet,
                'teks' => 'Meminta restock <b>' . e($row->nama_barang) . '</b> ke Gudang.',
                'icon' => 'ph-fill ph-truck',
                'color' => '#1565c0',
                'bg' => '#e3f2fd',
            ];
        }

        // Aktivitas penyetoran laporan hari ini.
        foreach (LaporanKeuangan::query()->where('tanggal', $hariIni)->orderByDesc('waktu_input')->limit(5)->get() as $row) {
            $timeline[] = [
                'waktu' => now()->setTimeFromTimeString((string) ($row->waktu_input ?? '00:00:00'))->toDateTimeString(),
                'outlet' => ucfirst((string) $row->kode_outlet),
                'teks' => 'Menyetor laporan dengan Omzet Bersih: <b style="color:#e65100;">Rp ' . number_format((float) $row->omzet_bersih, 0, ',', '.') . '</b>',
                'icon' => 'ph-fill ph-file-text',
                'color' => '#e65100',
                'bg' => '#fff3e0',
            ];
        }

        // Urutkan dari yang terbaru, ambil enam teratas.
        usort($timeline, fn ($a, $b) => strtotime($b['waktu']) <=> strtotime($a['waktu']));

        return array_slice($timeline, 0, 6);
    }

    // =================================================================
    // MODUL KELOLA MENU
    // =================================================================

    /**
     * Menampilkan daftar menu.
     */
    public function menuIndex()
    {
        return view('owner.menu.index', [
            'judul' => 'Kelola Menu - DFC Owner',
            'active' => 'menu',
            'menu' => Menu::query()->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Menampilkan form tambah atau ubah menu.
     */
    public function menuForm(?int $id = null)
    {
        $menu = $id
            ? Menu::query()->find($id)
            : new Menu(['id' => '', 'nama' => '', 'harga' => '', 'gambar' => '']);

        // Bila menu yang hendak diubah tidak ditemukan, kembali ke daftar.
        if ($id && ! $menu) {
            return redirect()->route('owner.menu.index')->with('gagal', 'Menu tidak ditemukan.');
        }

        return view('owner.menu.form', [
            'judul' => ($id ? 'Ubah' : 'Tambah') . ' Menu - DFC Owner',
            'active' => 'menu',
            'mode' => $id ? 'edit' : 'tambah',
            'menuItem' => $menu,
        ]);
    }

    /**
     * Menyimpan menu baru atau perubahan menu.
     */
    public function menuSimpan(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'nama' => ['required', 'string', 'max:150'],
            'harga' => ['required', 'integer', 'min:1'],
            'gambar_lama' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . (int) config('dfc.maks_gambar_kb')],
        ], [
            'nama.required' => 'Nama menu wajib diisi dengan benar.',
            'harga.min' => 'Nama menu dan harga wajib diisi dengan benar.',
        ]);

        $id = $data['id'] ?? null;
        $gambarFinal = (string) ($data['gambar_lama'] ?? '');

        // Proses unggahan gambar baru bila ada.
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaAman = preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $namaFile = time() . '_' . $namaAman;

            // Simpan berkas ke public/assets/img/owner.
            $file->move(public_path('assets/img/owner'), $namaFile);

            // Hapus gambar lama bila ada dan berbeda.
            if ($gambarFinal !== '') {
                $this->hapusGambarMenu($gambarFinal);
            }

            $gambarFinal = $namaFile;
        }

        // Perbarui menu lama atau buat menu baru.
        if ($id) {
            Menu::query()->whereKey($id)->update([
                'nama' => $data['nama'],
                'harga' => (int) $data['harga'],
                'gambar' => $gambarFinal,
            ]);
            $pesan = 'Menu berhasil diupdate.';
        } else {
            Menu::query()->create([
                'nama' => $data['nama'],
                'harga' => (int) $data['harga'],
                'gambar' => $gambarFinal,
            ]);
            $pesan = 'Menu baru berhasil ditambahkan.';
        }

        return redirect()->route('owner.menu.index')->with('sukses', $pesan);
    }

    /**
     * Menghapus menu beserta gambarnya.
     */
    public function menuHapus(int $id)
    {
        $menu = Menu::query()->find($id);

        if ($menu) {
            $this->hapusGambarMenu((string) $menu->gambar);
            $menu->delete();

            return redirect()->route('owner.menu.index')->with('sukses', 'Menu berhasil dihapus.');
        }

        return redirect()->route('owner.menu.index')->with('gagal', 'Menu tidak ditemukan.');
    }

    /**
     * Menghapus berkas gambar menu dari folder publik.
     */
    private function hapusGambarMenu(string $gambar): void
    {
        $namaFile = basename($gambar);
        if ($namaFile === '') {
            return;
        }

        $path = public_path('assets/img/owner/' . $namaFile);
        if (File::isFile($path)) {
            File::delete($path);
        }
    }

    // =================================================================
    // MODUL KELOLA LOKASI
    // =================================================================

    /**
     * Menampilkan daftar lokasi.
     */
    public function lokasiIndex()
    {
        return view('owner.lokasi.index', [
            'judul' => 'Kelola Lokasi - DFC Owner',
            'active' => 'lokasi',
            'lokasi' => Lokasi::query()->orderBy('kategori')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Menampilkan form tambah atau ubah lokasi.
     */
    public function lokasiForm(?int $id = null)
    {
        $lokasi = $id
            ? Lokasi::query()->find($id)
            : new Lokasi(['id' => '', 'nama' => '', 'alamat' => '', 'kategori' => '', 'latitude' => '', 'longitude' => '']);

        if ($id && ! $lokasi) {
            return redirect()->route('owner.lokasi.index')->with('gagal', 'Lokasi tidak ditemukan.');
        }

        return view('owner.lokasi.form', [
            'judul' => ($id ? 'Ubah' : 'Tambah') . ' Lokasi - DFC Owner',
            'active' => 'lokasi',
            'mode' => $id ? 'edit' : 'tambah',
            'lokasiItem' => $lokasi,
        ]);
    }

    /**
     * Menyimpan lokasi baru atau perubahan lokasi.
     */
    public function lokasiSimpan(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'nama' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string'],
            'kategori' => ['required', 'in:Pusat,Outlet,Mitra,Cabang'],
            'latitude' => ['required', 'string', 'max:50'],
            'longitude' => ['required', 'string', 'max:50'],
        ], [
            'nama.required' => 'Semua data lokasi wajib diisi.',
            'alamat.required' => 'Semua data lokasi wajib diisi.',
            'kategori.required' => 'Semua data lokasi wajib diisi.',
            'latitude.required' => 'Semua data lokasi wajib diisi.',
            'longitude.required' => 'Semua data lokasi wajib diisi.',
        ]);

        $id = $data['id'] ?? null;
        $payload = [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'kategori' => $data['kategori'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ];

        if ($id) {
            Lokasi::query()->whereKey($id)->update($payload);
            $pesan = 'Lokasi berhasil diupdate.';
        } else {
            Lokasi::query()->create($payload);
            $pesan = 'Lokasi baru berhasil ditambahkan.';
        }

        return redirect()->route('owner.lokasi.index')->with('sukses', $pesan);
    }

    /**
     * Menghapus lokasi.
     */
    public function lokasiHapus(int $id)
    {
        Lokasi::query()->whereKey($id)->delete();

        return redirect()->route('owner.lokasi.index')->with('sukses', 'Lokasi berhasil dihapus.');
    }

    // =================================================================
    // MODUL AKTIVASI OUTLET DAN KARYAWAN
    // =================================================================

    /**
     * Menampilkan halaman aktivasi outlet dan pengelolaan akun karyawan.
     */
    public function outletKaryawan()
    {
        return view('owner.outlet_karyawan', [
            'judul' => 'Aktivasi Outlet dan Karyawan - DFC Owner',
            'active' => 'lokasi',
            'lokasiBelumAktif' => Lokasi::query()->belumAktif()->orderBy('nama')->get(),
            'outletAktif' => Lokasi::query()->aktif()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Mengaktifkan outlet baru: membuat akun admin, mengisi lokasi,
     * menyiapkan pengaturan toko, dan menyiapkan stok awal tiap menu.
     */
    public function outletAktifkan(Request $request)
    {
        // Bersihkan username dari karakter selain huruf dan angka.
        $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $request->input('username', '')));

        $data = $request->validate([
            'id_lokasi' => ['required', 'integer', 'exists:lokasi,id'],
            'password' => ['required', 'string', 'min:4'],
            'telepon' => ['required', 'string', 'max:30'],
        ], [
            'id_lokasi.required' => 'Data aktivasi outlet wajib diisi lengkap.',
            'password.required' => 'Data aktivasi outlet wajib diisi lengkap.',
            'telepon.required' => 'Data aktivasi outlet wajib diisi lengkap.',
        ]);

        // Tolak bila username kosong setelah dibersihkan.
        if ($username === '') {
            return back()->with('gagal', 'Username outlet tidak valid.');
        }

        // Tolak bila username sudah dipakai akun lain.
        if (AdminOutlet::query()->where('username', $username)->exists()) {
            return back()->with('gagal', 'Username sudah terpakai.');
        }

        $lokasi = Lokasi::query()->findOrFail($data['id_lokasi']);

        // Jalankan rangkaian aktivasi dalam satu transaksi.
        DB::transaction(function () use ($lokasi, $username, $data) {
            // Buat akun admin outlet dengan password ter-hash.
            AdminOutlet::query()->create([
                'username' => $username,
                'password' => Hash::make($data['password']),
            ]);

            // Pasang akun dan telepon pada data lokasi.
            $lokasi->update([
                'telepon' => $data['telepon'],
                'username_admin' => $username,
            ]);

            // Siapkan pengaturan toko dengan status tutup bawaan.
            \App\Models\PengaturanToko::query()->updateOrCreate(
                ['kode_outlet' => $username],
                ['nama_outlet' => $lokasi->nama, 'status' => 'tutup', 'no_hp' => $data['telepon']]
            );

            // Siapkan baris stok nol untuk setiap menu yang sudah ada.
            foreach (Menu::query()->pluck('id') as $menuId) {
                \App\Models\StokOutlet::query()->firstOrCreate(
                    ['menu_id' => $menuId, 'kode_outlet' => $username],
                    ['stok' => 0]
                );
            }
        });

        return redirect()->route('owner.outlet_karyawan')->with('sukses', 'Outlet berhasil diaktifkan.');
    }

    /**
     * Memperbarui data telepon dan password akun outlet.
     */
    public function outletUpdate(Request $request)
    {
        $data = $request->validate([
            'id_lokasi_edit' => ['required', 'integer', 'exists:lokasi,id'],
            'telepon_edit' => ['required', 'string', 'max:30'],
            'username_lama' => ['required', 'string'],
            'password_edit' => ['nullable', 'string', 'min:4'],
        ], [
            'id_lokasi_edit.required' => 'Outlet dan nomor telepon wajib diisi.',
            'telepon_edit.required' => 'Outlet dan nomor telepon wajib diisi.',
        ]);

        // Perbarui nomor telepon pada data lokasi.
        Lokasi::query()->whereKey($data['id_lokasi_edit'])->update(['telepon' => $data['telepon_edit']]);
        \App\Models\PengaturanToko::query()
            ->where('kode_outlet', $data['username_lama'])
            ->update(['no_hp' => $data['telepon_edit']]);

        // Perbarui password bila diisi.
        if (! empty($data['password_edit'])) {
            AdminOutlet::query()
                ->where('username', $data['username_lama'])
                ->update(['password' => Hash::make($data['password_edit'])]);

            return redirect()->route('owner.outlet_karyawan')->with('sukses', 'Data outlet dan password berhasil diupdate.');
        }

        return redirect()->route('owner.outlet_karyawan')->with('sukses', 'Nomor telepon outlet berhasil diupdate.');
    }

    // =================================================================
    // MODUL STOK GUDANG PUSAT
    // =================================================================

    /**
     * Menampilkan halaman master stok gudang dan riwayatnya.
     */
    public function stokGudang()
    {
        return view('owner.stok_gudang', [
            'judul' => 'Stok Gudang Pusat - DFC Owner',
            'active' => 'stok-gudang',
            'stok' => StokGudang::query()->orderBy('stok')->orderBy('nama_barang')->get(),
            'riwayat' => RiwayatStok::query()->orderByDesc('id')->limit(50)->get(),
        ]);
    }

    /**
     * Menambahkan barang gudang baru.
     */
    public function stokGudangTambah(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($data) {
            StokGudang::query()->create([
                'nama_barang' => $data['nama_barang'],
                'stok' => (int) $data['stok'],
                'satuan' => $data['satuan'],
            ]);

            // Catat penambahan ke riwayat stok.
            RiwayatStok::query()->create([
                'nama_barang' => $data['nama_barang'],
                'stok_lama' => 0,
                'stok_baru' => (int) $data['stok'],
                'selisih' => (int) $data['stok'],
                'admin' => 'Owner (Baru)',
            ]);
        });

        return redirect()->route('owner.stok_gudang')->with('sukses', 'Barang gudang berhasil ditambahkan.');
    }

    /**
     * Memperbarui data barang gudang.
     */
    public function stokGudangEdit(Request $request)
    {
        $data = $request->validate([
            'id_barang' => ['required', 'integer', 'exists:stok_gudang,id'],
            'nama_barang' => ['required', 'string', 'max:150'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:30'],
        ]);

        $barang = StokGudang::query()->findOrFail($data['id_barang']);
        $stokLama = (int) $barang->stok;
        $stokBaru = (int) $data['stok'];
        $selisih = $stokBaru - $stokLama;

        DB::transaction(function () use ($barang, $data, $stokLama, $stokBaru, $selisih) {
            $barang->update([
                'nama_barang' => $data['nama_barang'],
                'stok' => $stokBaru,
                'satuan' => $data['satuan'],
            ]);

            // Catat ke riwayat hanya bila jumlah stok berubah.
            if ($selisih !== 0) {
                RiwayatStok::query()->create([
                    'nama_barang' => $data['nama_barang'],
                    'stok_lama' => $stokLama,
                    'stok_baru' => $stokBaru,
                    'selisih' => $selisih,
                    'admin' => 'Owner (Edit)',
                ]);
            }
        });

        return redirect()->route('owner.stok_gudang')->with('sukses', 'Stok gudang berhasil diupdate.');
    }

    /**
     * Menghapus barang gudang.
     */
    public function stokGudangHapus(int $id)
    {
        $barang = StokGudang::query()->find($id);

        if ($barang) {
            DB::transaction(function () use ($barang) {
                $barang->delete();

                RiwayatStok::query()->create([
                    'nama_barang' => $barang->nama_barang,
                    'stok_lama' => 0,
                    'stok_baru' => 0,
                    'selisih' => 0,
                    'admin' => 'Owner (Hapus)',
                ]);
            });
        }

        return redirect()->route('owner.stok_gudang')->with('sukses', 'Barang gudang berhasil dihapus.');
    }

    // =================================================================
    // MODUL PERMINTAAN STOK
    // =================================================================

    /**
     * Menampilkan daftar permintaan stok dari outlet.
     */
    public function requestStok()
    {
        return view('owner.request_stok', [
            'judul' => 'Permintaan Stok Outlet - DFC Owner',
            'active' => 'request-stok',
            'requests' => RequestStok::query()->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Menyetujui permintaan stok.
     */
    public function requestStokApprove(Request $request)
    {
        $data = $request->validate([
            'id_request' => ['required', 'integer', 'exists:request_stok,id'],
        ]);

        RequestStok::query()->whereKey($data['id_request'])->update([
            'status' => 'Disetujui',
            'alasan_tolak' => '-',
        ]);

        return redirect()->route('owner.request_stok')->with('sukses', 'Permintaan stok disetujui.');
    }

    /**
     * Menolak permintaan stok beserta alasannya.
     */
    public function requestStokTolak(Request $request)
    {
        $data = $request->validate([
            'id_request' => ['required', 'integer', 'exists:request_stok,id'],
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        RequestStok::query()->whereKey($data['id_request'])->update([
            'status' => 'Ditolak',
            'alasan_tolak' => trim((string) ($data['alasan'] ?? '-')) ?: '-',
        ]);

        return redirect()->route('owner.request_stok')->with('sukses', 'Permintaan stok ditolak.');
    }

    // =================================================================
    // MODUL PENGATURAN AKUN GUDANG
    // =================================================================

    /**
     * Menampilkan halaman pengaturan akun admin gudang.
     */
    public function aturGudang()
    {
        return view('owner.atur_gudang', [
            'judul' => 'Atur Password Gudang - DFC Owner',
            'active' => 'atur-gudang',
            'gudang' => AdminGudang::query()->first(),
        ]);
    }

    /**
     * Menyimpan perubahan akun admin gudang.
     */
    public function aturGudangSimpan(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        // Perbarui akun gudang pertama, buat bila belum ada.
        $gudang = AdminGudang::query()->first();
        if ($gudang) {
            $gudang->update([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
            ]);
        } else {
            AdminGudang::query()->create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
            ]);
        }

        return redirect()->route('owner.atur_gudang')->with('sukses', 'Akun admin gudang berhasil diupdate.');
    }

    // =================================================================
    // MODUL LAPORAN DAN RIWAYAT
    // =================================================================

    /**
     * Menampilkan laporan keuangan per bulan dan tahun.
     */
    public function laporanFull(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->format('m'));
        $tahun = (int) $request->input('tahun', now()->format('Y'));

        return view('owner.laporan', [
            'judul' => 'Laporan Keuangan - DFC Owner',
            'active' => 'laporan-full',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'laporan' => LaporanKeuangan::query()
                ->periode($bulan, $tahun)
                ->orderByDesc('tanggal')
                ->get(),
            'listBulan' => BantuanTanggal::BULAN,
        ]);
    }

    /**
     * Menampilkan riwayat penjualan web dengan penyaringan tanggal dan outlet.
     */
    public function riwayatPenjualan(Request $request)
    {
        $mulai = (string) $request->input('tgl_mulai', now()->toDateString());
        $akhir = (string) $request->input('tgl_akhir', now()->toDateString());
        $outlet = (string) $request->input('outlet', 'semua');

        $query = TransaksiPesanan::query()
            ->whereDate('waktu_pesan', '>=', $mulai)
            ->whereDate('waktu_pesan', '<=', $akhir);

        // Saring berdasarkan outlet bila bukan "semua".
        if ($outlet !== 'semua') {
            $query->where('kode_outlet', $outlet);
        }

        return view('owner.riwayat_penjualan', [
            'judul' => 'Riwayat Penjualan Web - DFC Owner',
            'active' => 'riwayat-penjualan',
            'tglMulai' => $mulai,
            'tglAkhir' => $akhir,
            'filterOutlet' => $outlet,
            'orders' => $query->orderByDesc('waktu_pesan')->get(),
            'outlets' => Lokasi::query()->aktif()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Menampilkan log aktivitas karyawan.
     */
    public function logAktivitas()
    {
        return view('owner.log_aktivitas', [
            'judul' => 'Log Aktivitas Karyawan - DFC Owner',
            'active' => 'log-aktivitas',
            'logs' => LogAktivitas::query()->orderByDesc('waktu')->limit(100)->get(),
        ]);
    }

    // =================================================================
    // MODUL PROFIL PERUSAHAAN
    // =================================================================

    /**
     * Menampilkan halaman pengaturan profil perusahaan.
     */
    public function profil()
    {
        return view('owner.profil', [
            'judul' => 'Atur Profil Perusahaan - DFC Owner',
            'active' => 'profil',
            'profil' => ProfilPerusahaan::ambil(),
        ]);
    }

    /**
     * Menyimpan perubahan profil perusahaan.
     */
    public function profilSimpan(Request $request)
    {
        $data = $request->validate([
            'sejarah' => ['required', 'string'],
            'visi' => ['required', 'string'],
            'misi' => ['required', 'string'],
        ]);

        ProfilPerusahaan::query()->updateOrCreate(
            ['id' => 1],
            [
                'sejarah' => $data['sejarah'],
                'visi' => $data['visi'],
                'misi' => $data['misi'],
            ]
        );

        return redirect()->route('owner.profil')->with('sukses', 'Profil perusahaan berhasil diperbarui.');
    }

    // =================================================================
    // MODUL DATA KEMITRAAN
    // =================================================================

    /**
     * Menampilkan daftar pengajuan kemitraan.
     */
    public function kemitraan()
    {
        return view('owner.kemitraan', [
            'judul' => 'Data Kemitraan - DFC Owner',
            'active' => 'kemitraan',
            'pengajuan' => PengajuanMitra::query()->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Menghapus data pengajuan kemitraan.
     */
    public function kemitraanHapus(int $id)
    {
        PengajuanMitra::query()->whereKey($id)->delete();

        return redirect()->route('owner.kemitraan')->with('sukses', 'Data pengajuan mitra berhasil dihapus.');
    }

    // =================================================================
    // MODUL AKUN MITRA
    // =================================================================

    /**
     * Menampilkan daftar akun mitra.
     */
    public function akunMitra()
    {
        return view('owner.akun_mitra', [
            'judul' => 'Akun Kemitraan - DFC Owner',
            'active' => 'akun-mitra',
            'akun' => AkunMitra::query()->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Membuat akun mitra baru.
     */
    public function akunMitraTambah(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        // Tolak bila username sudah dipakai.
        if (AkunMitra::query()->where('username', $data['username'])->exists()) {
            return back()->with('gagal', 'Username mitra sudah dipakai.');
        }

        AkunMitra::query()->create([
            'nama_mitra' => $data['nama'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('owner.akun_mitra')->with('sukses', 'Akun mitra berhasil dibuat.');
    }

    /**
     * Menghapus akun mitra.
     */
    public function akunMitraHapus(int $id)
    {
        AkunMitra::query()->whereKey($id)->delete();

        return redirect()->route('owner.akun_mitra')->with('sukses', 'Akun mitra berhasil dihapus.');
    }
}
