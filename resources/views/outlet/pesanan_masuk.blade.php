<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    {{-- Halaman menyegarkan diri tiap 10 detik agar pesanan baru segera tampil.
         Penyegaran dijeda otomatis bila petugas sedang menulis balasan chat. --}}
    <script>window.jedaSegarkan = 10000;</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7fc; color: #333; }

        /* HEADER MERAH OUTLET */
        header { background: #ff0000; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;}
        .back-btn { background: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
        .back-btn:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .page-title { display: flex; align-items: center; gap: 10px; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }

        /* KARTU PESANAN */
        .order-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 5px solid #ff9800; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;}
        .order-info { flex: 1; min-width: 300px; }
        .order-info h3 { margin: 0 0 10px 0; font-size: 18px; color: #333; display: flex; justify-content: space-between;}

        .order-info p { margin: 0 0 8px 0; font-size: 14px; color: #555; display: flex; align-items: center; gap: 8px; }
        .order-info p i { font-size: 18px; color: #8898aa; }

        .badge-status { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 10px; }
        .status-menunggu { background: #fff3e0; color: #e65100; }
        .status-disiapkan { background: #e3f2fd; color: #1565c0; }
        .status-siap { background: #e8f5e9; color: #2e7d32; }

        /* KOTAK DETAIL MENU (JSON) */
        .detail-box { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #eee; }
        .detail-box ul { margin: 0; padding-left: 20px; font-size: 14px; color: #333; }
        .detail-box li { margin-bottom: 5px; }
        .note-text { color: #e65100; font-size: 12px; font-style: italic; display: block; margin-top: 2px; }

        /* TOMBOL AKSI */
        .action-form { display: flex; flex-direction: column; gap: 10px; min-width: 150px; }
        .btn-action { padding: 12px 15px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; color: white; display: flex; align-items: center; justify-content: center; gap: 5px; transition: 0.2s; width: 100%;}
        .btn-proses { background: #2196F3; } .btn-proses:hover { background: #1976D2; }
        .btn-siap { background: #ff9800; } .btn-siap:hover { background: #f57c00; }
        .btn-selesai { background: #4CAF50; } .btn-selesai:hover { background: #388E3C; }

        /* PANEL CHAT DENGAN KONSUMEN */
        .chat-box { width: 100%; margin-top: 15px; border-top: 1px dashed #ddd; padding-top: 15px; }
        .chat-box h4 { margin: 0 0 10px 0; font-size: 14px; color: #333; display: flex; align-items: center; gap: 6px; }
        .chat-list { max-height: 260px; overflow-y: auto; background: #f7f9fc; border-radius: 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px; }
        .chat-bubble { max-width: 80%; padding: 9px 13px; border-radius: 12px; font-size: 13px; line-height: 1.45; word-wrap: break-word; }
        .chat-bubble .waktu { display: block; font-size: 10px; margin-top: 4px; opacity: 0.75; }
        .chat-masuk { align-self: flex-start; background: #ffffff; color: #333; border: 1px solid #e5e5e5; border-bottom-left-radius: 2px; }
        .chat-keluar { align-self: flex-end; background: #ff0000; color: #ffffff; border-bottom-right-radius: 2px; }
        .chat-sistem { align-self: center; background: #fff3cd; color: #856404; font-size: 12px; text-align: center; max-width: 90%; }
        .chat-bubble img { max-width: 100%; border-radius: 8px; margin-top: 6px; display: block; }
        .chat-kosong { text-align: center; color: #999; font-size: 12px; padding: 15px 0; }
        .chat-form { display: flex; gap: 8px; margin-top: 10px; }
        .chat-form input[type="text"] { flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; font-family: inherit; }
        .chat-form input[type="text"]:focus { outline: none; border-color: #ff0000; }
        .chat-form button { background: #ff0000; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; }
        .chat-form button:hover { background: #d10000; }
        .badge-chat { background: #ff0000; color: white; border-radius: 10px; padding: 1px 7px; font-size: 11px; font-weight: bold; margin-left: 5px; }
    </style>
</head>
<body>

    <header>
        <div style="font-size: 18px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
            <i class="ph-fill ph-bell-ringing"></i> Pesanan Web Masuk
        </div>
        <a href="{{ route('outlet.index') }}" class="back-btn"><i class="ph-bold ph-arrow-left"></i> Kembali</a>
    </header>

    <div class="container">
        <div class="page-title">
            <h2>Daftar Pesanan (Self-Pickup)</h2>
        </div>

        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; font-size: 13px; color: #856404; margin-bottom: 20px; border-left: 4px solid #ffeeba; display:flex; align-items: flex-start; gap:8px;">
            <i class="ph-fill ph-info" style="font-size:18px; margin-top: 2px;"></i>
            <span>Halaman ini akan refresh otomatis tiap 10 detik. Jika pesanan sudah Selesai, jangan lupa potong stok di halaman depan menggunakan tombol merah.</span>
        </div>

        @if ($orders->isEmpty())
            <div style="background:white; padding:40px; text-align:center; border-radius:12px; color:#888; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">Belum ada pesanan masuk saat ini.</div>
        @else
            @foreach ($orders as $order)
                @php
                    // Tentukan warna lencana berdasarkan status pesanan.
                    $status = $order->status_pesanan;
                    $badgeClass = '';
                    if ($status == 'Menunggu Konfirmasi') $badgeClass = 'status-menunggu';
                    if ($status == 'Sedang Disiapkan') $badgeClass = 'status-disiapkan';
                    if ($status == 'Siap Diambil') $badgeClass = 'status-siap';
                @endphp

                <div class="order-card">
                    <div class="order-info">
                        <h3>#{{ $order->order_id }}
                            <span style="font-size: 13px; color: #888; font-weight: normal; display: flex; align-items: center; gap: 4px;">
                                <i class="ph-fill ph-clock" style="font-size: 16px;"></i> {{ \Illuminate\Support\Carbon::parse($order->waktu_pesan)->format('H:i') }}
                            </span>
                        </h3>

                        <p><i class="ph-fill ph-user"></i> <b>{{ $order->nama_pemesan }}</b> ({{ $order->no_hp }})</p>
                        <p><i class="ph-fill ph-credit-card"></i> Pembayaran: <b>{{ $order->metode_bayar }}</b></p>
                        <p><i class="ph-fill ph-money" style="color: #ff0000;"></i> Total Bayar: <b style="color: #ff0000; font-size: 16px;">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</b></p>

                        <div class="detail-box">
                            <strong style="font-size:12px; color:#555; display:flex; align-items:center; gap:5px; margin-bottom: 8px;">
                                <i class="ph-fill ph-shopping-bag"></i> DETAIL PESANAN:
                            </strong>
                            <ul>
                                @php $items = $order->items; @endphp
                                @if (is_array($items))
                                    @foreach ($items as $item)
                                        <li><b>{{ $item['qty'] }}x {{ $item['nama'] }}</b>
                                            @if (! empty($item['catatan']))
                                                <span class="note-text">Catatan: {{ $item['catatan'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li>Gagal memuat detail</li>
                                @endif
                            </ul>
                        </div>

                        <div class="badge-status {{ $badgeClass }}">{{ $status }}</div>
                    </div>

                    {{-- Tombol perubahan status pesanan sesuai tahap saat ini --}}
                    <div class="action-form">
                        <form method="POST" action="{{ route('outlet.pesanan_masuk.status') }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">

                            @if ($status == 'Menunggu Konfirmasi')
                                <input type="hidden" name="status_baru" value="Sedang Disiapkan">
                                <button type="submit" name="update_status" class="btn-action btn-proses">
                                    <i class="ph-bold ph-cooking-pot"></i> Terima & Masak
                                </button>
                            @elseif ($status == 'Sedang Disiapkan')
                                <input type="hidden" name="status_baru" value="Siap Diambil">
                                <button type="submit" name="update_status" class="btn-action btn-siap">
                                    <i class="ph-bold ph-shopping-bag"></i> Makanan Siap
                                </button>
                            @elseif ($status == 'Siap Diambil')
                                <input type="hidden" name="status_baru" value="Selesai">
                                <button type="submit" name="update_status" class="btn-action btn-selesai">
                                    <i class="ph-bold ph-check-circle"></i> Selesaikan
                                </button>
                            @endif
                        </form>
                    </div>

                    {{-- Percakapan dengan konsumen untuk pesanan ini --}}
                    @php
                        $pesanPesanan = $percakapan[$order->order_id] ?? collect();
                        $belumDibaca = (int) ($jumlahBelumDibaca[$order->order_id] ?? 0);
                    @endphp

                    <div class="chat-box">
                        <h4>
                            <i class="ph-fill ph-chat-circle-dots" style="color:#ff0000;"></i>
                            Percakapan Konsumen
                            @if ($belumDibaca > 0)
                                <span class="badge-chat">{{ $belumDibaca }} baru</span>
                            @endif
                        </h4>

                        <div class="chat-list">
                            @forelse ($pesanPesanan as $pesan)
                                @php
                                    // Tentukan gaya gelembung sesuai asal pesan.
                                    if ($pesan->pengirim === 'outlet') {
                                        $kelas = 'chat-keluar';
                                    } elseif ($pesan->pengirim === 'konsumen') {
                                        $kelas = 'chat-masuk';
                                    } else {
                                        $kelas = 'chat-sistem';
                                    }
                                @endphp

                                <div class="chat-bubble {{ $kelas }}">
                                    {!! $pesan->pesan_aman !!}
                                    <span class="waktu">
                                        @if ($pesan->pengirim === 'outlet')
                                            Anda
                                        @elseif ($pesan->pengirim === 'konsumen')
                                            Konsumen
                                        @else
                                            Sistem
                                        @endif
                                        &middot; {{ $pesan->waktu ? $pesan->waktu->format('d/m H:i') : '' }}
                                    </span>
                                </div>
                            @empty
                                <div class="chat-kosong">Belum ada pesan dari konsumen.</div>
                            @endforelse
                        </div>

                        <form class="chat-form" method="POST" action="{{ route('outlet.pesanan_masuk.chat') }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                            <input type="text" name="pesan" class="kolom-chat" placeholder="Balas pesan konsumen..." maxlength="1000" autocomplete="off" required>
                            <button type="submit"><i class="ph-bold ph-paper-plane-tilt"></i> Kirim</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        (function () {
            // Geser setiap daftar chat ke pesan paling baru.
            document.querySelectorAll('.chat-list').forEach(function (daftar) {
                daftar.scrollTop = daftar.scrollHeight;
            });

            // Segarkan halaman berkala, tetapi tunda bila petugas sedang
            // mengetik balasan agar tulisannya tidak hilang.
            if (window.jedaSegarkan) {
                setInterval(function () {
                    var kolomAktif = document.activeElement;
                    if (kolomAktif && kolomAktif.classList.contains('kolom-chat')) {
                        return;
                    }
                    var sedangMenulis = Array.prototype.some.call(
                        document.querySelectorAll('.kolom-chat'),
                        function (kolom) { return kolom.value.trim() !== ''; }
                    );
                    if (sedangMenulis) {
                        return;
                    }
                    window.location.reload();
                }, window.jedaSegarkan);
            }
        })();
    </script>
</body>
</html>
