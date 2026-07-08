<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-gear" style="color:#ff0000;"></i> Atur Akses Gudang</h2></div>
    <?php if(!empty($data['flash'])): ?><div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div><?php endif; ?>
    <div class="native-panel owner-form" style="max-width:600px; margin:0 auto;">
        <h2 class="native-panel-title">Edit Username & Password Gudang</h2>
        <div class="native-panel-body native-form">
            <div class="native-info"><i class="ph-fill ph-info"></i> <b>Akun Aktif (ID: 1)</b><br>Username: <b><?= htmlspecialchars($data['gudang']['username'] ?? ''); ?></b></div>
        <form method="POST" action="<?= BASEURL; ?>/owner/atur-gudang">
            <label>Username Gudang</label><input type="text" name="username" value="<?= htmlspecialchars($data['gudang']['username'] ?? ''); ?>" required>
            <label>Password Gudang</label><input type="text" name="password" value="<?= htmlspecialchars($data['gudang']['password'] ?? ''); ?>" required>
            <small style="display:block; margin-top:-12px; margin-bottom:25px; color:#666;">*Password disimpan langsung agar sesuai dengan sistem login gudang lama.</small>
            <button class="btn btn-add" style="width:100%; justify-content:center;"><i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan</button>
        </form>
        </div>
    </div>
</div>
