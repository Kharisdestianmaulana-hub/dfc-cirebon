<?php
$menu = $data['menu_item'];
$isEdit = $data['mode'] === 'edit';
$fileName = basename($menu['gambar'] ?? '');
$localFile = dirname(__DIR__, 4) . '/assets/img/owner/' . $fileName;
$imageUrl = ($fileName && is_file($localFile)) ? BASEURL . '/assets/img/owner/' . rawurlencode($fileName) : '';
?>
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-hamburger" style="color:#ff0000;"></i> <?= $isEdit ? 'Edit' : 'Tambah'; ?> Menu</h2>
        <a href="<?= BASEURL; ?>/owner/menu" class="owner-btn owner-btn-muted">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if(!empty($data['error'])): ?>
        <div class="alert-owner alert-error"><?= htmlspecialchars($data['error']); ?></div>
    <?php endif; ?>

    <div class="owner-card owner-form">
        <form method="POST" action="<?= BASEURL; ?>/owner/menu/simpan" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($menu['id'] ?? ''); ?>">
            <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($menu['gambar'] ?? ''); ?>">

            <label>Nama Menu</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($menu['nama'] ?? ''); ?>" placeholder="Cth: Ayam Geprek" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($menu['harga'] ?? ''); ?>" placeholder="Cth: 15000" min="1" required>

            <label>Foto Menu</label>
            <?php if($imageUrl): ?>
                <img src="<?= $imageUrl; ?>" class="current-image" alt="Foto menu saat ini">
            <?php elseif($isEdit && !empty($menu['gambar'])): ?>
                <p style="color:#b71c1c; font-size:13px; margin-top:0;">File gambar lama tidak ditemukan: <?= htmlspecialchars($menu['gambar']); ?></p>
            <?php endif; ?>
            <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <small style="display:block; margin-top:-12px; margin-bottom:20px; color:#777;">Kosongkan jika tidak ingin mengganti foto.</small>

            <div class="owner-actions" style="margin-bottom:0;">
                <button type="submit" class="owner-btn owner-btn-primary">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                </button>
                <a href="<?= BASEURL; ?>/owner/menu" class="owner-btn owner-btn-muted">Batal</a>
            </div>
        </form>
    </div>
</div>
