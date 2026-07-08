<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-pencil-simple" style="color:#ff0000;"></i> Atur Profil Perusahaan</h2></div>
    <?php if(!empty($data['flash'])): ?><div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div><?php endif; ?>
    <div class="native-panel owner-form" style="max-width:800px; margin:0 auto;">
        <h2 class="native-panel-title">Edit Konten Halaman 'Tentang Kami'</h2>
        <div class="native-panel-body native-form">
        <form method="POST" action="<?= BASEURL; ?>/owner/profil">
            <label>Sejarah / Cerita Kami</label><textarea name="sejarah" rows="6" required><?= htmlspecialchars($data['profil']['sejarah'] ?? ''); ?></textarea>
            <label>Visi Perusahaan</label><textarea name="visi" rows="4" required><?= htmlspecialchars($data['profil']['visi'] ?? ''); ?></textarea>
            <label>Misi Perusahaan</label><textarea name="misi" rows="4" required><?= htmlspecialchars($data['profil']['misi'] ?? ''); ?></textarea>
            <small style="display:block; margin-top:-12px; margin-bottom:25px; color:#667085;"><i>Gunakan tombol Enter untuk membuat baris baru</i></small>
            <button class="btn btn-add" style="width:100%; justify-content:center;"><i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan</button>
        </form>
        </div>
    </div>
</div>
