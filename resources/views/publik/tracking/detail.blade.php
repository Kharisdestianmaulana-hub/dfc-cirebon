@php
    // Tentukan status tiap langkah pada timeline pelacakan.
    $status = $pesanan->status_pesanan;
    $step1 = $step2 = $step3 = $step4 = '';

    if ($status == 'Menunggu Konfirmasi') { $step1 = 'active'; }
    elseif ($status == 'Sedang Disiapkan') { $step1 = 'done'; $step2 = 'active'; }
    elseif ($status == 'Siap Diambil') { $step1 = 'done'; $step2 = 'done'; $step3 = 'active'; }
    elseif ($status == 'Selesai') { $step1 = 'done'; $step2 = 'done'; $step3 = 'done'; $step4 = 'active'; }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fd020f" />
    <title>{{ $judul }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Halaman menyegarkan diri selama pesanan belum selesai. Penyegaran
         dijeda otomatis bila konsumen sedang menulis pesan chat. --}}
    @if ($status != 'Selesai')
    <script>
        window.jedaSegarkan = 5000;
    </script>
    @endif

    <style>
        body { background: #f9f9f9; font-family: sans-serif; }
        .track-container { max-width: 500px; margin: 30px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px;}
        .header h2 { margin: 0 0 5px 0; color: #333; }
        .header p { margin: 0; color: #888; font-size: 14px; }
        .order-id { background: #ffebee; color: #fd020f; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-family: monospace; font-size: 16px; margin-top: 10px; display: inline-block;}

        /* CSS TIMELINE TRACKING */
        .timeline { position: relative; padding-left: 30px; margin-top: 30px; }
        .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 3px; background: #eee; }

        .step { position: relative; padding-bottom: 40px; }
        .step:last-child { padding-bottom: 0; }

        .step .icon { position: absolute; left: -36px; top: 0; width: 32px; height: 32px; border-radius: 50%; background: #eee; color: #aaa; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 4px solid white; z-index: 2; transition: 0.3s;}
        .step h4 { margin: 0; font-size: 15px; color: #aaa; }
        .step p { margin: 5px 0 0; font-size: 12px; color: #bbb; }

        /* Status Aktif */
        .step.active .icon { background: #ff9800; color: white; box-shadow: 0 0 0 4px #ffe0b2; animation: pulse 2s infinite; }
        .step.active h4 { color: #ff9800; font-weight: bold; }
        .step.active p { color: #666; }

        /* Status Selesai Dilewati */
        .step.done .icon { background: #4caf50; color: white; }
        .step.done h4 { color: #4caf50; }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 152, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0); }
        }

        .btn-wa { display: block; width: 100%; text-align: center; background: #25D366; color: white; padding: 15px; border-radius: 8px; font-weight: bold; text-decoration: none; margin-top: 30px; font-size: 16px; transition: 0.2s;}
        .btn-wa:hover { background: #1ebe57; }

        /* Gaya tombol kembali dinamis */
        .btn-kembali-dinamis { background: none; border: none; color: #666; font-size: 14px; cursor: pointer; padding: 0; text-decoration: none; font-family: inherit;}
        .btn-kembali-dinamis:hover { color: #fd020f; text-decoration: underline; }

        /* PANEL CHAT KONSUMEN DENGAN OUTLET */
        .chat-panel { margin-top: 30px; border-top: 2px dashed #eee; padding-top: 20px; }
        .chat-panel h3 { margin: 0 0 5px 0; font-size: 16px; color: #333; display: flex; align-items: center; gap: 8px; }
        .chat-panel .chat-sub { margin: 0 0 15px 0; font-size: 12px; color: #888; }
        .chat-list { max-height: 320px; overflow-y: auto; background: #f7f7f7; border-radius: 10px; padding: 15px; display: flex; flex-direction: column; gap: 12px; }
        .chat-bubble { max-width: 82%; padding: 10px 14px; border-radius: 12px; font-size: 14px; line-height: 1.45; word-wrap: break-word; }
        .chat-bubble .waktu { display: block; font-size: 11px; margin-top: 5px; opacity: 0.7; }
        .chat-masuk { align-self: flex-start; background: #ffffff; color: #333; border: 1px solid #e5e5e5; border-bottom-left-radius: 2px; }
        .chat-keluar { align-self: flex-end; background: #fd020f; color: #ffffff; border-bottom-right-radius: 2px; }
        .chat-sistem { align-self: center; background: #fff3cd; color: #856404; font-size: 12px; text-align: center; max-width: 90%; }
        .chat-kosong { text-align: center; color: #999; font-size: 13px; padding: 20px 0; }
        .chat-form { display: flex; gap: 8px; margin-top: 12px; }
        .chat-form input[type="text"] { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: inherit; }
        .chat-form input[type="text"]:focus { outline: none; border-color: #fd020f; }
        .chat-form button { background: #fd020f; color: #fff; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .chat-form button:hover { background: #d10009; }
    </style>
</head>
<body>

    <div class="track-container">
        <div class="header">
            <h2>Status Pesanan</h2>
            <p>Atas Nama: <b>{{ $pesanan->nama_pemesan }}</b></p>
            <div class="order-id">#{{ $orderId }}</div>

            @if ($status != 'Selesai')
                <p style="margin-top:10px; font-size: 12px; color: #fd020f;"><i class="fa-solid fa-rotate"></i> Halaman ini akan otomatis refresh.</p>
            @else
                <p style="margin-top:10px; font-size: 12px; color: #4CAF50;"><i class="fa-solid fa-check"></i> Pesanan telah selesai.</p>
            @endif
        </div>

        {{-- Timeline status pesanan --}}
        <div class="timeline">
            <div class="step {{ $step1 }}">
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <h4>Menunggu Konfirmasi</h4>
                <p>Pesanan Anda telah diterima sistem dan menunggu konfirmasi Kasir Outlet.</p>
            </div>

            <div class="step {{ $step2 }}">
                <div class="icon"><i class="fas fa-fire-burner"></i></div>
                <h4>Sedang Disiapkan</h4>
                <p>Ayam Anda sedang disiapkan dan dikemas. Harap bersabar ya!</p>
            </div>

            <div class="step {{ $step3 }}">
                <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                <h4>Siap Diambil</h4>
                <p>Yeay! Makanan Anda sudah siap. Silakan ambil di kasir tanpa perlu antre.</p>
            </div>

            <div class="step {{ $step4 }}">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <h4>Selesai</h4>
                <p>Terima kasih telah memesan DFC! Selamat menikmati.</p>
            </div>
        </div>

        <a href="https://wa.me/{{ config('dfc.wa_admin') }}?text=Halo%20Admin,%20saya%20mau%20tanya%20pesanan%20dengan%20ID%20{{ urlencode($orderId) }}" target="_blank" class="btn-wa">
            <i class="fab fa-whatsapp"></i> Chat Admin Outlet
        </a>

        {{-- Panel percakapan konsumen dengan outlet --}}
        <div class="chat-panel">
            <h3><i class="fas fa-comments" style="color:#fd020f;"></i> Chat dengan Outlet</h3>
            <p class="chat-sub">Tanyakan langsung kepada petugas outlet mengenai pesanan ini.</p>

            <div class="chat-list" id="daftarChat">
                @forelse ($percakapan as $pesan)
                    @php
                        // Tentukan gaya gelembung sesuai asal pesan.
                        if ($pesan->pengirim === 'konsumen') {
                            $kelas = 'chat-keluar';
                        } elseif ($pesan->pengirim === 'outlet') {
                            $kelas = 'chat-masuk';
                        } else {
                            $kelas = 'chat-sistem';
                        }
                    @endphp

                    <div class="chat-bubble {{ $kelas }}">
                        {!! $pesan->pesan_aman !!}
                        <span class="waktu">
                            @if ($pesan->pengirim === 'outlet')
                                Outlet
                            @elseif ($pesan->pengirim === 'konsumen')
                                Anda
                            @else
                                Sistem
                            @endif
                            &middot; {{ $pesan->waktu ? $pesan->waktu->format('d/m H:i') : '' }}
                        </span>
                    </div>
                @empty
                    <div class="chat-kosong">Belum ada percakapan. Silakan kirim pesan pertama Anda.</div>
                @endforelse
            </div>

            <form class="chat-form" method="POST" action="{{ route('tracking.chat') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $orderId }}">
                <input type="hidden" name="source" value="{{ $source }}">
                <input type="hidden" name="phone_key" value="{{ $phoneKey }}">
                <input type="text" name="pesan" id="pesanKonsumen" placeholder="Tulis pesan untuk outlet..." maxlength="1000" autocomplete="off" required>
                <button type="submit"><i class="fas fa-paper-plane"></i> Kirim</button>
            </form>
        </div>

        @if ($status == 'Selesai')
            <a href="{{ route('pesanan.struk', ['id' => $orderId]) }}" target="_blank" style="display: block; width: 100%; text-align: center; background: #333; color: white; padding: 15px; border-radius: 8px; font-weight: bold; text-decoration: none; margin-top: 15px; font-size: 16px; transition: 0.2s;">
                <i class="fa-solid fa-receipt"></i> Cetak Struk Pembelian
            </a>
        @endif

        <div style="text-align: center; margin-top: 25px;">
            @if ($source == 'riwayat' && ! empty($phoneKey))
                <form action="{{ route('tracking.riwayat') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="no_hp" value="{{ $phoneKey }}">
                    <input type="hidden" name="dari_tracking" value="1">
                    <button type="submit" class="btn-kembali-dinamis">&larr; Kembali ke Hasil Riwayat</button>
                </form>
            @else
                <a href="{{ url('/') }}" class="btn-kembali-dinamis">&larr; Kembali ke Beranda</a>
            @endif
        </div>
    </div>

    <script>
        (function () {
            // Geser daftar chat ke pesan paling baru.
            var daftar = document.getElementById('daftarChat');
            if (daftar) {
                daftar.scrollTop = daftar.scrollHeight;
            }

            // Segarkan halaman secara berkala, tetapi tunda bila konsumen
            // sedang mengetik pesan agar tulisannya tidak hilang.
            if (window.jedaSegarkan) {
                setInterval(function () {
                    var kolom = document.getElementById('pesanKonsumen');
                    if (kolom && (document.activeElement === kolom || kolom.value.trim() !== '')) {
                        return;
                    }
                    window.location.reload();
                }, window.jedaSegarkan);
            }
        })();
    </script>

</body>
</html>
