<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <style>
        body {
            background: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: sans-serif;
            margin: 0;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .mitra-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-top: 5px solid #ff9800;
        }
        .mitra-card h2 { text-align: center; color: #333; margin: 0 0 10px 0; font-weight: 800; font-size: 22px; }
        .mitra-card p.subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 30px; line-height: 1.5; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box; transition: 0.3s; margin: 0; }
        .form-control:focus { border-color: #ff9800; outline: none; background: #fffbfb; }
        textarea.form-control { resize: vertical; }
        .btn-submit { width: 100%; background-color: #ff9800; color: white; padding: 15px; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); }
        .btn-submit:hover { background: #e65100; transform: translateY(-2px); }
        .back-link { display: block; text-align: center; margin-top: 25px; color: #888; text-decoration: none; font-size: 14px; }
        .back-link:hover { color: #ff0000; text-decoration: underline; }
        .alert-error { background: #ffe0e0; color: #c62828; border: 1px solid #ffcdd2; border-radius: 8px; padding: 12px; font-size: 13px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="mitra-card">
        <h2>Formulir Pengajuan Mitra</h2>
        <p class="subtitle">Tertarik membuka cabang DFC di kota Anda? Isi formulir di bawah ini dan tim kami akan segera menghubungi Anda!</p>

        {{-- Tampilkan pesan kesalahan validasi bila ada --}}
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('kemitraan.kirim') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Masukkan nama Anda" required>
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" class="form-control" placeholder="Contoh: 0812xxxxxxxx" required>
            </div>
            <div class="form-group">
                <label>Rencana Lokasi Usaha (Kota/Kecamatan)</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-control" placeholder="Contoh: Cirebon Kota" required>
            </div>
            <div class="form-group">
                <label>Pesan Tambahan / Pertanyaan</label>
                <textarea name="pesan" class="form-control" rows="4" placeholder="Tulis pesan Anda di sini...">{{ old('pesan') }}</textarea>
            </div>
            <button type="submit" class="btn-submit">Kirim Pengajuan</button>
        </form>

        <a href="{{ route('beranda') }}" class="back-link">&larr; Kembali ke Beranda</a>
    </div>
</body>
</html>
