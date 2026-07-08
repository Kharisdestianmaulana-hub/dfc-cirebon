<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-user-gear" style="color:#ff0000;"></i> Aktivasi Outlet & Karyawan</h2>
        <a href="<?= BASEURL; ?>/owner/lokasi" class="owner-btn owner-btn-muted">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if(!empty($data['flash'])): ?>
        <div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div>
    <?php endif; ?>
    <?php if(!empty($data['error'])): ?>
        <div class="alert-owner alert-error"><?= htmlspecialchars($data['error']); ?></div>
    <?php endif; ?>

    <div class="owner-two-col">
        <div class="owner-card owner-form">
            <h3 style="margin-top:0;">Aktivasi Outlet Baru</h3>
            <p style="color:#666; font-size:13px; margin-top:-6px; margin-bottom:20px;">Buat akun login karyawan untuk lokasi yang belum aktif.</p>

            <form method="POST" action="<?= BASEURL; ?>/owner/outlet-karyawan/aktifkan">
                <label>Pilih Lokasi</label>
                <select name="id_lokasi" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <?php foreach($data['lokasi_belum_aktif'] as $lokasi): ?>
                        <option value="<?= $lokasi['id']; ?>">
                            <?= htmlspecialchars($lokasi['nama'] . ' (' . $lokasi['kategori'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
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

        <div class="owner-card owner-form">
            <h3 style="margin-top:0;">Edit Data Outlet</h3>
            <p style="color:#666; font-size:13px; margin-top:-6px; margin-bottom:20px;">Update nomor telepon atau reset password akun karyawan.</p>

            <form method="POST" action="<?= BASEURL; ?>/owner/outlet-karyawan/update">
                <label>Pilih Outlet Aktif</label>
                <select name="id_lokasi_edit" id="pilihEditOutlet" onchange="isiFormOutlet()" required>
                    <option value="">-- Pilih Outlet --</option>
                    <?php foreach($data['outlet_aktif'] as $outlet): ?>
                        <option value="<?= $outlet['id']; ?>"
                                data-telp="<?= htmlspecialchars($outlet['telepon'] ?? ''); ?>"
                                data-user="<?= htmlspecialchars($outlet['username_admin'] ?? ''); ?>">
                            <?= htmlspecialchars($outlet['nama']); ?>
                        </option>
                    <?php endforeach; ?>
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
    function isiFormOutlet() {
        const select = document.getElementById('pilihEditOutlet');
        const option = select.options[select.selectedIndex];
        document.getElementById('telpEditOutlet').value = option.getAttribute('data-telp') || '';
        document.getElementById('userLamaOutlet').value = option.getAttribute('data-user') || '';
    }
</script>
