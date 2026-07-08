<?php
$lokasi = $data['lokasi_item'];
$isEdit = $data['mode'] === 'edit';
$kategori = $lokasi['kategori'] ?? '';
?>
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-map-pin" style="color:#ff0000;"></i> <?= $isEdit ? 'Edit' : 'Tambah'; ?> Lokasi</h2>
        <a href="<?= BASEURL; ?>/owner/lokasi" class="owner-btn owner-btn-muted">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if(!empty($data['error'])): ?>
        <div class="alert-owner alert-error"><?= htmlspecialchars($data['error']); ?></div>
    <?php endif; ?>

    <div class="owner-card owner-form">
        <form method="POST" action="<?= BASEURL; ?>/owner/lokasi/simpan">
            <input type="hidden" name="id" value="<?= htmlspecialchars($lokasi['id'] ?? ''); ?>">

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Pusat" <?= $kategori === 'Pusat' ? 'selected' : ''; ?>>Pusat</option>
                <option value="Outlet" <?= $kategori === 'Outlet' ? 'selected' : ''; ?>>Outlet</option>
                <option value="Mitra" <?= $kategori === 'Mitra' ? 'selected' : ''; ?>>Mitra</option>
                <option value="Cabang" <?= $kategori === 'Cabang' ? 'selected' : ''; ?>>Cabang</option>
            </select>

            <label>Nama Outlet</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($lokasi['nama'] ?? ''); ?>" placeholder="Cth: Mitra 1 (Riki) - Pemuda" required>

            <label>Alamat Lengkap</label>
            <textarea name="alamat" rows="3" placeholder="Cth: Jl. Pemuda, Sunyaragi, samping Kodim..." required><?= htmlspecialchars($lokasi['alamat'] ?? ''); ?></textarea>

            <div class="owner-form-row">
                <div>
                    <label>Latitude</label>
                    <input type="text" name="latitude" value="<?= htmlspecialchars($lokasi['latitude'] ?? ''); ?>" placeholder="Cth: -6.732023" required>
                </div>
                <div>
                    <label>Longitude</label>
                    <input type="text" name="longitude" value="<?= htmlspecialchars($lokasi['longitude'] ?? ''); ?>" placeholder="Cth: 108.552316" required>
                </div>
            </div>

            <div style="background:#e3f2fd; padding:15px; border-radius:8px; font-size:13px; color:#0d47a1; margin-bottom:20px;">
                <strong>Cara mendapatkan koordinat:</strong><br>
                Buka Google Maps, klik kanan titik lokasi, lalu salin angka koordinat. Angka pertama untuk Latitude, angka kedua untuk Longitude.
            </div>

            <div class="owner-actions" style="margin-bottom:0;">
                <button type="submit" class="owner-btn owner-btn-primary">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Lokasi
                </button>
                <a href="<?= BASEURL; ?>/owner/lokasi" class="owner-btn owner-btn-muted">Batal</a>
            </div>
        </form>
    </div>
</div>
