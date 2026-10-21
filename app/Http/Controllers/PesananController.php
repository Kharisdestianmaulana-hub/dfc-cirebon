<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Menu;
use App\Models\PengaturanToko;
use App\Models\StokOutlet;
use App\Models\TransaksiPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Controller Pesanan.
 * Mengurus alur pemesanan konsumen: memilih menu, checkout, memproses
 * pesanan, menampilkan halaman pembayaran QRIS, dan mencetak struk.
 */
class PesananController extends Controller
{
    /**
     * Halaman daftar menu untuk memesan.
     * Menyiapkan pengelompokan stok per outlet agar tampilan stok langsung
     * dapat dibaca JavaScript tanpa perlu permintaan tambahan.
     */
    public function index()
    {
        $menu = Menu::query()->orderByDesc('id')->get();
        $outlets = Lokasi::query()->aktif()->where('kategori', '!=', 'Pusat')->orderBy('nama')->get();

        // Peta stok: [kode_outlet][menu_id] => jumlah stok.
        $petaStok = StokOutlet::query()
            ->get()
            ->groupBy('kode_outlet')
            ->map(fn ($baris) => $baris->pluck('stok', 'menu_id')->all())
            ->all();

        // Peta status toko: kode_outlet => status.
        $statusToko = PengaturanToko::petaStatus();

        return view('publik.pesanan.index', [
            'judul' => 'Pesan - DFC Cirebon',
            'menu' => $menu,
            'outlets' => $outlets,
            'petaStok' => $petaStok,
            'statusToko' => $statusToko,
        ]);
    }

    /**
     * Endpoint JSON status seluruh outlet, dipanggil berkala halaman pesanan.
     */
    public function statusOutlet()
    {
        // Kembalikan peta status per kode outlet.
        $data = PengaturanToko::query()
            ->get(['kode_outlet', 'nama_outlet', 'status'])
            ->keyBy('kode_outlet')
            ->toArray();

        return response()->json($data);
    }

    /**
     * Halaman konfirmasi pesanan (checkout).
     * Menerima data keranjang dari halaman pesanan.
     */
    public function checkout(Request $request)
    {
        $cartJson = (string) $request->input('cart_data', '');

        // Bila data keranjang kosong, kembali ke halaman pesanan.
        if ($cartJson === '') {
            return redirect()->route('pesanan.index');
        }

        return view('publik.pesanan.checkout', [
            'judul' => 'Checkout - Dexpress',
            'cartJson' => $cartJson,
            'cart' => json_decode($cartJson, true) ?: [],
            'outletNama' => (string) $request->input('outlet_name', ''),
            'outletTelepon' => (string) $request->input('outlet_phone', ''),
        ]);
    }

    /**
     * Memproses pesanan: menyimpan transaksi lalu mengarahkan ke
     * halaman pembayaran QRIS atau halaman pelacakan pesanan.
     */
    public function prosesCheckout(Request $request)
    {
        $dataValid = $request->validate([
            'cust_name' => ['required', 'string', 'max:150'],
            'cust_phone' => ['required', 'string', 'max:30'],
            'payment' => ['required', 'in:Cash,QRIS'],
            'total_final' => ['required', 'numeric', 'min:0'],
            'cart_data' => ['required', 'string'],
            'outlet_name' => ['required', 'string'],
        ], [
            'cust_name.required' => 'Nama pemesan wajib diisi.',
            'cust_phone.required' => 'Nomor HP wajib diisi.',
            'cart_data.required' => 'Data keranjang tidak ditemukan.',
        ]);

        // Cari kode outlet berdasarkan nama outlet yang dipilih konsumen.
        $kodeOutlet = Lokasi::query()
            ->where('nama', $dataValid['outlet_name'])
            ->value('username_admin');

        // Bila outlet tidak ditemukan, buat kode dari nama sebagai cadangan.
        if (empty($kodeOutlet)) {
            $kodeOutlet = strtolower(str_replace(' ', '', $dataValid['outlet_name']));
        }

        // Bentuk kode pesanan unik: DFC-<ddmmyy>-<4 karakter unik>.
        $orderId = 'DFC-' . now()->format('dmy') . '-' . strtoupper(substr(uniqid(), -4));

        $pesanan = TransaksiPesanan::query()->create([
            'order_id' => $orderId,
            'nama_pemesan' => $dataValid['cust_name'],
            'no_hp' => $dataValid['cust_phone'],
            'kode_outlet' => $kodeOutlet,
            'total_bayar' => (float) $dataValid['total_final'],
            'metode_bayar' => $dataValid['payment'],
            'detail_pesanan' => $dataValid['cart_data'],
            'status_pesanan' => 'Menunggu Konfirmasi',
        ]);

        // Arahkan ke halaman pembayaran QRIS atau pelacakan sesuai metode.
        if ($dataValid['payment'] === 'QRIS') {
            return redirect()->route('pesanan.qris', ['orderId' => $pesanan->order_id]);
        }

        return redirect()->route('tracking.detail', [
            'id' => $pesanan->order_id,
            'source' => 'checkout',
            'phone_key' => $dataValid['cust_phone'],
        ]);
    }

    /**
     * Halaman instruksi pembayaran QRIS.
     */
    public function qris(string $orderId)
    {
        $pesanan = TransaksiPesanan::query()->where('order_id', $orderId)->first();

        // Bila pesanan tidak ada, hentikan dengan pesan jelas.
        if (! $pesanan) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('publik.pesanan.qris', [
            'judul' => 'Pembayaran QRIS - ' . $orderId,
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Halaman cetak struk pesanan.
     */
    public function struk(string $orderId)
    {
        $pesanan = TransaksiPesanan::query()->where('order_id', $orderId)->first();

        // Bila pesanan tidak ada, hentikan dengan pesan jelas.
        if (! $pesanan) {
            abort(404, 'Pesanan tidak valid.');
        }

        return view('publik.pesanan.struk', [
            'judul' => 'Struk Pembelian #' . $orderId,
            'pesanan' => $pesanan,
            'items' => $pesanan->items,
        ]);
    }
}
