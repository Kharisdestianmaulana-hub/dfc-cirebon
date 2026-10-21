@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-user-gear" style="color:#ff0000;"></i> Aktivasi Outlet & Karyawan</h2>
        <a href="{{ route('owner.lokasi.index') }}" class="owner-btn owner-btn-muted">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="owner-two-col">
        {{-- Formulir aktivasi outlet baru --}}
        <div class="owner-card owner-form">
            <h3 style="margin-top:0;">Aktivasi Outlet Baru</h3>
            <p style="color:#666; font-size:13px; margin-top:-6px; margin-bottom:20px;">Buat akun login karyawan untuk lokasi yang belum aktif.</p>

            <form method="POST" action="{{ route('owner.outlet_karyawan.aktifkan') }}">
                @csrf
                <label>Pilih Lokasi</label>
                <select name="id_lokasi" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach ($lokasiBelumAktif as $lokasi)
                        <option value="{{ $lokasi->id }}">
                            {{ $lokasi->nama . ' (' . $lokasi->kategori . ')' }}
                        </option>
                    @endforeach
                </select>

                <label>Username Login</label>
                <input type="text" name="username" placeholder="cth: cabangbaru" required>

                <label>Password Login</label>
                <input type="text" name="password" placeholder="Password karyawan" required>

                <label>Nomor Telepon</label>
                <input type="text" name="telepon" placeholder="cth: 62812xxxx" required>

                <button type="submit" class="owner-btn owner-btn-primary" style="width:100%; justify-content:center;">
                    <i class="ph-bold ph-check-circle"></i> Simpan & Aktifkan Outlet
                </button>
            </form>
        </div>

        {{-- Formulir edit data outlet aktif --}}
        <div class="owner-card owner-form">
            <h3 style="margin-top:0;">Edit Data Outlet</h3>
            <p style="color:#666; font-size:13px; margin-top:-6px; margin-bottom:20px;">Update nomor telepon atau reset password akun karyawan.</p>

            <form method="POST" action="{{ route('owner.outlet_karyawan.update') }}">
                @csrf
                <label>Pilih Outlet Aktif</label>
                <select name="id_lokasi_edit" id="pilihEditOutlet" onchange="isiFormOutlet()" required>
                    <option value="">-- Pilih Outlet --</option>
                    @foreach ($outletAktif as $outlet)
                        <option value="{{ $outlet->id }}"
                                data-telp="{{ $outlet->telepon ?? '' }}"
                                data-user="{{ $outlet->username_admin ?? '' }}">
                            {{ $outlet->nama }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="username_lama" id="userLamaOutlet">

                <label>Nomor Telepon</label>
                <input type="text" name="telepon_edit" id="telpEditOutlet" placeholder="Nomor telepon outlet" required>

                <label>Ganti Password</label>
                <input type="text" name="password_edit" placeholder="Opsional, isi jika ingin reset password">

                <button type="submit" class="owner-btn owner-btn-edit" style="width:100%; justify-content:center;">
                    <i class="ph-bold ph-floppy-disk"></i> Update Data
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Isi otomatis telepon dan username saat outlet dipilih.
    function isiFormOutlet() {
        const select = document.getElementById('pilihEditOutlet');
        const option = select.options[select.selectedIndex];
        document.getElementById('telpEditOutlet').value = option.getAttribute('data-telp') || '';
        document.getElementById('userLamaOutlet').value = option.getAttribute('data-user') || '';
    }
</script>
@endsection
