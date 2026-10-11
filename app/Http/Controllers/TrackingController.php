<?php

namespace App\Http\Controllers;

use App\Models\ChatPesanan;
use App\Models\TransaksiPesanan;
use Illuminate\Http\Request;

/**
 * Controller Tracking.
 * Melayani pencarian riwayat pesanan konsumen berdasarkan nomor HP
 * dan menampilkan detail pelacakan status pesanan.
 */
class TrackingController extends Controller
{
    /**
     * Halaman cek riwayat pesanan.
     * Bila nomor HP dikirim, tampilkan pesanan aktif dan pesanan selesai.
     */
    public function riwayat(Request $request)
    {
        $noHp = (string) $request->input('no_hp', '');
        $sudahDicari = false;
        $pesananAktif = collect();
        $pesananSelesai = collect();

        // Lakukan pencarian bila form dikirim atau berasal dari halaman pelacakan.
        if ($request->filled('no_hp') && ($request->filled('cari') || $request->filled('dari_tracking'))) {
            $sudahDicari = true;

            $pesananAktif = TransaksiPesanan::query()
                ->where('no_hp', $noHp)
                ->belumSelesai()
                ->orderByDesc('waktu_pesan')
                ->get();

            $pesananSelesai = TransaksiPesanan::query()
                ->where('no_hp', $noHp)
                ->status('Selesai')
                ->orderByDesc('waktu_pesan')
                ->get();
        }

        return view('publik.tracking.riwayat', [
            'judul' => 'Cek Riwayat Pesanan - DFC Cirebon',
            'noHp' => $noHp,
            'sudahDicari' => $sudahDicari,
            'pesananAktif' => $pesananAktif,
            'pesananSelesai' => $pesananSelesai,
        ]);
    }

    /**
     * Halaman detail pelacakan status pesanan.
     */
    public function detail(Request $request)
    {
        $orderId = (string) $request->input('id', '');

        // Bila order id kosong, hentikan dengan pesan jelas.
        if ($orderId === '') {
            abort(404, 'Order ID tidak ditemukan.');
        }

        $pesanan = TransaksiPesanan::query()->where('order_id', $orderId)->first();

        // Bila pesanan tidak ada, hentikan dengan pesan jelas.
        if (! $pesanan) {
            abort(404, 'Pesanan tidak valid.');
        }

        // Ambil seluruh percakapan pesanan ini, lalu tandai balasan outlet
        // sebagai sudah dibaca karena konsumen sedang membuka halamannya.
        $percakapan = ChatPesanan::query()
            ->where('order_id', $orderId)
            ->orderBy('waktu')
            ->orderBy('id')
            ->get();

        ChatPesanan::query()
            ->where('order_id', $orderId)
            ->where('pengirim', 'outlet')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('publik.tracking.detail', [
            'judul' => 'Lacak Pesanan - DFC',
            'pesanan' => $pesanan,
            'orderId' => $orderId,
            'source' => (string) $request->input('source', ''),
            'phoneKey' => (string) $request->input('phone_key', ''),
            'percakapan' => $percakapan,
        ]);
    }

    /**
     * Mengirim pesan chat konsumen untuk sebuah pesanan.
     */
    public function kirimChat(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string', 'exists:transaksi_pesanan,order_id'],
            'pesan' => ['required', 'string', 'max:1000'],
        ]);

        ChatPesanan::query()->create([
            'order_id' => $data['order_id'],
            'pengirim' => 'konsumen',
            'pesan' => trim($data['pesan']),
            'is_read' => false,
            'waktu' => now(),
        ]);

        return redirect()->route('tracking.detail', [
            'id' => $data['order_id'],
            'source' => (string) $request->input('source', ''),
            'phone_key' => (string) $request->input('phone_key', ''),
        ]);
    }
}
