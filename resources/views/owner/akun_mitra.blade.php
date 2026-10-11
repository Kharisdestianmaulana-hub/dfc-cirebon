@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-users-three" style="color:#ff0000;"></i> Kelola Akun Mitra</h2></div>

    <style>
        .akun-mitra-layout {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) minmax(520px, 2fr);
            gap: 30px;
            align-items: start;
            width: 100%;
            margin: 25px 0 0;
        }
        .akun-mitra-form { padding: 25px; min-height: 350px; }
        .akun-mitra-form h2, .akun-mitra-list h2 { margin: 0 0 24px; font-size: 24px; color: #333; font-weight: 800; }
        .akun-mitra-form label { display: block; margin-bottom: 8px; font-size: 14px; color: #444; font-weight: 700; }
        .akun-mitra-form input { width: 100%; height: 42px; padding: 0 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 16px; box-sizing: border-box; font-size: 14px; font-family: 'Segoe UI', sans-serif; }
        .akun-mitra-form .btn-add { width: 100%; justify-content: center; margin: 6px 0 0; padding: 12px 20px; min-height: 40px; box-shadow: 0 8px 16px rgba(255,0,0,0.18); }
        .akun-mitra-list { padding: 25px; min-height: 350px; }
        .akun-mitra-list table { min-width: 0; }
        .akun-mitra-list .native-table thead th { background: #fafafa; padding: 16px 20px; }
        .akun-mitra-list .native-table tbody td { padding: 18px 20px; font-size: 14px; }
        .akun-mitra-list .btn-del { padding: 8px 16px; font-weight: 700; }
        @media (max-width: 1100px) {
            .akun-mitra-layout { grid-template-columns: 1fr; gap: 20px; }
        }
    </style>

    <div class="akun-mitra-layout">
        {{-- Formulir pembuatan akun mitra baru --}}
        <div class="card akun-mitra-form">
            <h2>Buat Akun Baru</h2>
            <form method="POST" action="{{ route('owner.akun_mitra.tambah') }}">
                @csrf
                <label>Nama Mitra / Outlet</label><input type="text" name="nama" placeholder="Contoh: Mitra Cirebon 1" required>
                <label>Username</label><input type="text" name="username" placeholder="Tanpa spasi" required>
                <label>Password</label><input type="text" name="password" placeholder="Buat sandi..." required>
                <button class="btn btn-add">Buat Akun</button>
            </form>
        </div>
        {{-- Daftar akun mitra yang sudah ada --}}
        <div class="card akun-mitra-list">
            <h2>Daftar Mitra Aktif</h2>
            <table class="native-table"><thead><tr><th>Nama Mitra</th><th>Username</th><th>Aksi</th></tr></thead><tbody>
                @foreach ($akun as $a)
                    <tr>
                        <td><b>{{ $a->nama_mitra }}</b></td>
                        <td>{{ $a->username }}</td>
                        <td><form method="POST" action="{{ route('owner.akun_mitra.hapus', ['id' => $a->id]) }}" class="form-confirm">@csrf<button class="btn btn-del">Hapus</button></form></td>
                    </tr>
                @endforeach
            </tbody></table>
        </div>
    </div>
</div>
@endsection
