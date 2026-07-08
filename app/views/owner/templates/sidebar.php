<?php
$active = $data['active'] ?? 'dashboard';
$isActive = function($keys) use ($active) {
    return in_array($active, (array)$keys, true) ? 'active' : '';
};
?>
<div class="sidebar" id="mySidebar">
    <div class="sidebar-header">
        <div class="brand">
            <div class="brand-icon"><i class="ph-fill ph-crown"></i></div>
            <div class="brand-text">DFC OWNER</div>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-title">MAIN MENU</div>
        <a href="<?= BASEURL; ?>/owner/dashboard" class="<?= $isActive('dashboard'); ?>"><i class="ph ph-squares-four"></i> Dashboard</a>
        <a href="<?= BASEURL; ?>/owner/laporan-full" class="<?= $isActive(['laporan-full', 'laporan']); ?>"><i class="ph ph-money"></i> Laporan Keuangan</a>
        <a href="<?= BASEURL; ?>/owner/stok-gudang" class="<?= $isActive('stok-gudang'); ?>"><i class="ph ph-package"></i> Stok Gudang Pusat</a>
        <a href="<?= BASEURL; ?>/owner/request-stok" class="<?= $isActive('request-stok'); ?>"><i class="ph ph-bell-ringing"></i> Permintaan Stok</a>
        <a href="<?= BASEURL; ?>/owner/atur-gudang" class="<?= $isActive('atur-gudang'); ?>"><i class="ph ph-gear"></i> Atur Password Gudang</a>

        <div class="menu-title">WEBSITE CONTROL</div>
        <a href="<?= BASEURL; ?>/owner/menu" class="<?= $isActive(['menu', 'kelola-menu']); ?>"><i class="ph ph-hamburger"></i> Kelola Menu Makanan</a>
        <a href="<?= BASEURL; ?>/owner/lokasi" class="<?= $isActive(['lokasi', 'kelola-lokasi']); ?>"><i class="ph ph-map-pin"></i> Kelola Lokasi Outlet</a>
        <a href="<?= BASEURL; ?>/owner/log-aktivitas" class="<?= $isActive('log-aktivitas'); ?>"><i class="ph ph-scroll"></i> Log Aktivitas Karyawan</a>
        <a href="<?= BASEURL; ?>/owner/riwayat-penjualan" class="<?= $isActive('riwayat-penjualan'); ?>"><i class="ph ph-receipt"></i> Riwayat Penjualan</a>
        <a href="<?= BASEURL; ?>/owner/profil" class="<?= $isActive(['profil', 'atur-profil']); ?>"><i class="ph ph-pencil-simple"></i> Atur Profil Perusahaan</a>

        <div class="menu-title">KEMITRAAN</div>
        <a href="<?= BASEURL; ?>/owner/kemitraan" class="<?= $isActive(['kemitraan', 'kelola-kemitraan']); ?>"><i class="ph ph-handshake"></i> Data Kemitraan</a>
        <a href="<?= BASEURL; ?>/owner/akun-mitra" class="<?= $isActive('akun-mitra'); ?>"><i class="ph ph-users-three"></i> Akun Kemitraan</a>
    </div>

    <div class="sidebar-footer">
        <a href="<?= BASEURL; ?>/owner/logout"><i class="ph ph-sign-out"></i> Logout</a>
    </div>
</div>
