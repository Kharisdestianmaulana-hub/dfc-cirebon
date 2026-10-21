<?php

namespace App\Http\Controllers;

use App\Models\ChatPesanan;
use App\Models\LaporanKeuangan;
use App\Models\LogAktivitas;
use App\Models\Menu;
use App\Models\PengaturanToko;
use App\Models\PenjualanHarian;
use App\Models\RequestStok;
use App\Models\StokOutlet;
use App\Models\TransaksiPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Controller Outlet.
 * Menggabungkan seluruh fitur folder native lama "outlet/" ke dalam Laravel:
 * login karyawan, panel stok, catat penjualan, pesanan web masuk,
 * laporan keuangan, dan permintaan restock.
 */
class OutletController extends Controller
{
    /**
     * Mengambil kode outlet (username admin) dari pengguna yang sedang login.
     */
    private function kodeOutlet(): string
    {
        return (string) Auth::guard('outlet')->user()->username;
    }

    /**
     * Mengambil nama outlet dari data lokasi milik pengguna yang sedang login.
     */
    private function namaOutlet(): string
    {
        $lokasi = Auth::guard('outlet')->user()->lokasi;

        return $lokasi->nama ?? ('Outlet ' . ucfirst($this->kodeOutlet()));
    }

    /**
     * Menampilkan halaman login karyawan outlet.
     */
    public function tampilLogin()
    {
        // Bila sudah login, langsung arahkan ke panel stok.
        if (Auth::guard('outlet')->check()) {
            return redirect()->route('outlet.index');
        }

        return view('outlet.login', [
            'judul' => 'Login Karyawan Outlet DFC Cirebon',
        ]);
    }

    /**
     * Memproses login karyawan outlet.
     */
    public function prosesLogin(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login memakai guard outlet.
        if (Auth::guard('outlet')->attempt($kredensial)) {
            $request->session()->regenerate();

            // Catat aktivitas login ke log.
            LogAktivitas::catat(
                $this->kodeOutlet(),
                'Login Masuk',
                'Admin ' . $this->kodeOutlet() . ' berhasil login ke sistem.'
            );

            return redirect()->route('outlet.index');
        }

        return back()->withErrors([
            'login' => 'Username atau Password salah!',
        ])->withInput();
    }

    /**
     * Menampilkan panel stok dan penjualan outlet.
     */
    public function index()
    {
        $kodeOutlet = $this->kodeOutlet();

        // Ambil seluruh menu beserta stok milik outlet ini.
        $menus = Menu::query()
            ->with(['stokOutlet' => fn ($q) => $q->where('kode_outlet', $kodeOutlet)])
            ->get()
            ->map(function (Menu $menu) {
                // Sisipkan stok outlet agar view tidak perlu query tambahan.
                $menu->stok_outlet = optional($menu->stokOutlet->first())->stok ?? 0;

                return $menu;
            });

        // Status toko saat ini, bawaan "tutup" bila belum diatur.
        $statusSekarang = PengaturanToko::query()
            ->where('kode_outlet', $kodeOutlet)
            ->value('status') ?? 'tutup';

        // Riwayat penjualan hari ini milik outlet ini.
        $riwayatPenjualan = PenjualanHarian::query()
            ->where('kode_outlet', $kodeOutlet)
            ->whereDate('tanggal', now()->toDateString())
            ->orderByDesc('id')
            ->get();

        return view('outlet.index', [
            'judul' => 'Panel Outlet - DFC Cirebon',
            'kodeOutlet' => $kodeOutlet,
            'namaOutlet' => $this->namaOutlet(),
            'menus' => $menus,
            'statusSekarang' => $statusSekarang,
            'riwayatPenjualan' => $riwayatPenjualan,
        ]);
    }

    /**
     * Mencatat penjualan offline dan mengurangi stok outlet terkait.
     */
    public function simpanPenjualan(Request $request)
    {
        $data = $request->validate([
            'id_menu' => ['required', 'integer', 'exists:menu,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'catatan_rasa' => ['required', 'string', 'max:255'],
        ]);

        $kodeOutlet = $this->kodeOutlet();
        $menu = Menu::query()->findOrFail($data['id_menu']);
        $qty = (int) $data['qty'];
        $harga = (float) $menu->harga;

        // Simpan penjualan lalu kurangi stok dalam satu transaksi.
        DB::transaction(function () use ($kodeOutlet, $menu, $qty, $harga, $data) {
            PenjualanHarian::query()->create([
                'tanggal' => now()->toDateString(),
                'waktu' => now()->format('H:i:s'),
                'kode_outlet' => $kodeOutlet,
                'nama_menu' => $menu->nama,
                'qty' => $qty,
                'catatan' => $data['catatan_rasa'],
                'harga_satuan' => $harga,
                'subtotal' => $harga * $qty,
            ]);

            // Kurangi stok outlet, tidak boleh kurang dari nol.
            $stok = StokOutlet::query()->firstOrCreate(
                ['menu_id' => $menu->id, 'kode_outlet' => $kodeOutlet],
                ['stok' => 0]
            );
            $stok->update(['stok' => max(0, (int) $stok->stok - $qty)]);
        });

        // Catat aktivitas ke log.
        LogAktivitas::catat($kodeOutlet, 'Catat Penjualan', 'Menjual penjualan offline: ' . $menu->nama);

        return redirect()
            ->route('outlet.index')
            ->with('sukses', 'Berhasil! Penjualan dicatat dan stok berkurang.');
    }

    /**
     * Memperbarui stok sebuah menu pada outlet ini.
     */
    public function updateStok(Request $request)
    {
        $data = $request->validate([
            'id_menu' => ['required', 'integer', 'exists:menu,id'],
            'stok_baru' => ['required', 'integer', 'min:0'],
        ]);

        $kodeOutlet = $this->kodeOutlet();
        $menu = Menu::query()->findOrFail($data['id_menu']);

        // Perbarui atau buat baris stok untuk menu ini pada outlet ini.
        $stok = StokOutlet::query()->firstOrCreate(
            ['menu_id' => $menu->id, 'kode_outlet' => $kodeOutlet],
            ['stok' => 0]
        );
        $stok->update(['stok' => (int) $data['stok_baru']]);

        LogAktivitas::catat($kodeOutlet, 'Update Stok', 'Ubah stok ' . $menu->nama);

        return redirect()
            ->route('outlet.index')
            ->with('sukses', 'Stok diupdate!');
    }

    /**
     * Mengubah status buka atau tutup toko.
     */
    public function ubahStatus(Request $request)
    {
        $data = $request->validate([
            'status_toko' => ['required', 'in:buka,tutup'],
        ]);

        $kodeOutlet = $this->kodeOutlet();

        // Perbarui status toko, buat baris bila belum ada.
        PengaturanToko::query()->updateOrCreate(
            ['kode_outlet' => $kodeOutlet],
            [
                'status' => $data['status_toko'],
                'nama_outlet' => $this->namaOutlet(),
            ]
        );

        LogAktivitas::catat($kodeOutlet, 'Ubah Status Toko', 'Status toko diperbarui');

        return redirect()
            ->route('outlet.index')
            ->with('sukses', 'Tersimpan! Status outlet berhasil diubah.');
    }

    /**
     * Mengirim permintaan restock bahan baku ke gudang pusat.
     */
    public function kirimRequest(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'satuan' => ['required', 'string', 'max:30'],
        ]);

        RequestStok::query()->create([
            'nama_outlet' => $this->namaOutlet(),
            'nama_barang' => trim($data['nama_barang']),
            'jumlah' => (int) $data['jumlah'],
            'satuan' => trim($data['satuan']),
            'status' => 'Pending',
        ]);

        LogAktivitas::catat($this->kodeOutlet(), 'Request Stok', 'Meminta restock ' . $data['nama_barang']);

        return redirect()
            ->route('outlet.index')
            ->with('sukses', 'Terkirim! Permintaan restock sudah dikirim ke Gudang Pusat.');
    }

    /**
     * Menampilkan riwayat permintaan restock (dipanggil berkala via AJAX).
     */
    public function riwayatRequest()
    {
        // Ambil lima permintaan terakhir milik outlet ini.
        $riwayat = RequestStok::query()
            ->where('nama_outlet', $this->namaOutlet())
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Kembalikan sebagai potongan tampilan agar mudah dipasang di halaman.
        return view('outlet.riwayat_request', [
            'requestHistory' => $riwayat,
        ]);
    }

    /**
     * Menampilkan halaman pesanan web masuk untuk outlet ini.
     */
    public function pesananMasuk()
    {
        $kodeOutlet = $this->kodeOutlet();

        // Ambil pesanan yang belum selesai dan ditujukan ke outlet ini.
        $orders = TransaksiPesanan::query()
            ->where('kode_outlet', 'like', '%' . $kodeOutlet . '%')
            ->belumSelesai()
            ->orderBy('waktu_pesan')
            ->get();

        // Kumpulkan percakapan untuk seluruh pesanan yang tampil.
        $daftarOrderId = $orders->pluck('order_id')->all();

        $percakapan = ChatPesanan::query()
            ->whereIn('order_id', $daftarOrderId)
            ->orderBy('waktu')
            ->orderBy('id')
            ->get()
            ->groupBy('order_id');

        // Hitung jumlah pesan konsumen yang belum dibaca per pesanan.
        $jumlahBelumDibaca = ChatPesanan::query()
            ->whereIn('order_id', $daftarOrderId)
            ->where('pengirim', 'konsumen')
            ->where('is_read', false)
            ->selectRaw('order_id, COUNT(*) as jumlah')
            ->groupBy('order_id')
            ->pluck('jumlah', 'order_id');

        // Tandai pesan konsumen sebagai sudah dibaca karena outlet membuka halaman ini.
        ChatPesanan::query()
            ->whereIn('order_id', $daftarOrderId)
            ->where('pengirim', 'konsumen')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('outlet.pesanan_masuk', [
            'judul' => 'Pesanan Web Masuk - ' . $this->namaOutlet(),
            'kodeOutlet' => $kodeOutlet,
            'namaOutlet' => $this->namaOutlet(),
            'orders' => $orders,
            'percakapan' => $percakapan,
            'jumlahBelumDibaca' => $jumlahBelumDibaca,
        ]);
    }

    /**
     * Mengirim balasan chat outlet untuk sebuah pesanan.
     */
    public function kirimChat(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string'],
            'pesan' => ['required', 'string', 'max:1000'],
        ]);

        $kodeOutlet = $this->kodeOutlet();

        // Pastikan pesanan memang ditujukan ke outlet yang sedang login.
        $pesanan = TransaksiPesanan::query()
            ->where('order_id', $data['order_id'])
            ->where('kode_outlet', 'like', '%' . $kodeOutlet . '%')
            ->first();

        if (! $pesanan) {
            abort(404, 'Pesanan tidak ditemukan untuk outlet ini.');
        }

        ChatPesanan::query()->create([
            'order_id' => $data['order_id'],
            'pengirim' => 'outlet',
            'pesan' => trim($data['pesan']),
            'is_read' => false,
            'waktu' => now(),
        ]);

        return redirect()->route('outlet.pesanan_masuk');
    }

    /**
     * Memperbarui status sebuah pesanan web.
     */
    public function updateStatusPesanan(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string', 'exists:transaksi_pesanan,order_id'],
            'status_baru' => ['required', 'string', 'in:' . implode(',', TransaksiPesanan::STATUS)],
        ]);

        TransaksiPesanan::query()
            ->where('order_id', $data['order_id'])
            ->update(['status_pesanan' => $data['status_baru']]);

        return redirect()->route('outlet.pesanan_masuk');
    }

    /**
     * Menampilkan halaman laporan keuangan outlet.
     */
    public function laporan()
    {
        $kodeOutlet = $this->kodeOutlet();

        // Ambil laporan terbaru dan data grafik tujuh laporan terakhir.
        $reports = LaporanKeuangan::query()
            ->where('kode_outlet', $kodeOutlet)
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        $grafik = LaporanKeuangan::query()
            ->where('kode_outlet', $kodeOutlet)
            ->orderByDesc('tanggal')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        return view('outlet.laporan', [
            'judul' => 'Laporan Keuangan - ' . $this->namaOutlet(),
            'kodeOutlet' => $kodeOutlet,
            'namaOutlet' => $this->namaOutlet(),
            'reports' => $reports,
            'labels' => $grafik->map(fn (LaporanKeuangan $row) => \App\Support\BantuanTanggal::tanggalSingkat($row->tanggal))->all(),
            'dataOmzet' => $grafik->pluck('omzet_bersih')->map(fn ($v) => (float) $v)->all(),
        ]);
    }

    /**
     * Menyimpan laporan keuangan harian outlet.
     */
    public function simpanLaporan(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'omzet_kotor' => ['required', 'numeric', 'min:0'],
            'pengeluaran' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $kodeOutlet = $this->kodeOutlet();
        $omzetKotor = (float) $data['omzet_kotor'];
        $pengeluaran = (float) $data['pengeluaran'];

        // Perbarui laporan bila sudah ada, buat baru bila belum ada.
        LaporanKeuangan::query()->updateOrCreate(
            [
                'tanggal' => $data['tanggal'],
                'kode_outlet' => $kodeOutlet,
            ],
            [
                'omzet_kotor' => $omzetKotor,
                'pengeluaran' => $pengeluaran,
                'omzet_bersih' => $omzetKotor - $pengeluaran,
                'catatan' => (string) ($data['catatan'] ?? ''),
                'waktu_input' => now()->format('H:i:s'),
            ]
        );

        return redirect()->route('outlet.laporan');
    }

    /**
     * Memproses logout karyawan outlet.
     */
    public function logout(Request $request)
    {
        Auth::guard('outlet')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('outlet.login');
    }
}
