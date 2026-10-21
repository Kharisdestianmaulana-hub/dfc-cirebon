@php
    // Tentukan menu sidebar yang sedang aktif.
    $active = $active ?? 'dashboard';
    $isActive = function ($keys) use ($active) {
        return in_array($active, (array) $keys, true) ? 'active' : '';
    };
@endphp
<div class="sidebar" id="mySidebar">
    <div class="sidebar-header">
        <div class="brand">
            <div class="brand-icon"><i class="ph-fill ph-crown"></i></div>
            <div class="brand-text">DFC OWNER</div>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-title">MAIN MENU</div>
        <a href="{{ route('owner.dashboard') }}" class="{{ $isActive('dashboard') }}"><i class="ph ph-squares-four"></i> Dashboard</a>
        <a href="{{ route('owner.laporan_full') }}" class="{{ $isActive(['laporan-full', 'laporan']) }}"><i class="ph ph-money"></i> Laporan Keuangan</a>
        <a href="{{ route('owner.stok_gudang') }}" class="{{ $isActive('stok-gudang') }}"><i class="ph ph-package"></i> Stok Gudang Pusat</a>
        <a href="{{ route('owner.request_stok') }}" class="{{ $isActive('request-stok') }}"><i class="ph ph-bell-ringing"></i> Permintaan Stok</a>
        <a href="{{ route('owner.atur_gudang') }}" class="{{ $isActive('atur-gudang') }}"><i class="ph ph-gear"></i> Atur Password Gudang</a>

        <div class="menu-title">WEBSITE CONTROL</div>
        <a href="{{ route('owner.menu.index') }}" class="{{ $isActive(['menu', 'kelola-menu']) }}"><i class="ph ph-hamburger"></i> Kelola Menu Makanan</a>
        <a href="{{ route('owner.lokasi.index') }}" class="{{ $isActive(['lokasi', 'kelola-lokasi']) }}"><i class="ph ph-map-pin"></i> Kelola Lokasi Outlet</a>
        <a href="{{ route('owner.log_aktivitas') }}" class="{{ $isActive('log-aktivitas') }}"><i class="ph ph-scroll"></i> Log Aktivitas Karyawan</a>
        <a href="{{ route('owner.riwayat_penjualan') }}" class="{{ $isActive('riwayat-penjualan') }}"><i class="ph ph-receipt"></i> Riwayat Penjualan</a>
        <a href="{{ route('owner.profil') }}" class="{{ $isActive(['profil', 'atur-profil']) }}"><i class="ph ph-pencil-simple"></i> Atur Profil Perusahaan</a>

        <div class="menu-title">KEMITRAAN</div>
        <a href="{{ route('owner.kemitraan') }}" class="{{ $isActive(['kemitraan', 'kelola-kemitraan']) }}"><i class="ph ph-handshake"></i> Data Kemitraan</a>
        <a href="{{ route('owner.akun_mitra') }}" class="{{ $isActive('akun-mitra') }}"><i class="ph ph-users-three"></i> Akun Kemitraan</a>
    </div>

    <div class="sidebar-footer">
        {{-- Logout owner memakai form POST agar aman --}}
        <form method="POST" action="{{ route('owner.logout') }}" style="margin:0;">
            @csrf
            <button type="submit" style="width:100%; display:flex; align-items:center; justify-content:center; gap:10px; padding:12px; background:#fff5f5; color:#ff4757; border:none; font-weight:600; border-radius:8px; cursor:pointer; font-size:14px; font-family:'Segoe UI', sans-serif;">
                <i class="ph ph-sign-out"></i> Logout
            </button>
        </form>
    </div>
</div>
