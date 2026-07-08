<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-map-pin" style="color:#ff0000;"></i> Kelola Lokasi Outlet</h2>
    </div>

    <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <a href="<?= BASEURL; ?>/owner/lokasi/form" class="btn btn-add" style="margin:0;">
            <i class="ph-bold ph-plus"></i> Tambah Lokasi
        </a>
        <a href="<?= BASEURL; ?>/owner/outlet-karyawan" class="btn" style="background:#2d3436; color:white;">
            <i class="ph-bold ph-user-gear"></i> Aktivasi & Akun Karyawan
        </a>
    </div>

    <?php if(!empty($data['flash'])): ?>
        <div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div>
    <?php endif; ?>

    <div class="card">
        <table class="native-table" style="min-width:950px;">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Nama Outlet</th>
                    <th>Info Akun</th>
                    <th>Koordinat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['lokasi'])): ?>
                    <tr><td colspan="5" style="text-align:center; color:#999; padding:30px;">Belum ada lokasi.</td></tr>
                <?php endif; ?>

                <?php foreach($data['lokasi'] as $lokasi): ?>
                    <?php
                    $kategori = strtolower($lokasi['kategori'] ?? 'cabang');
                    $badge = 'badge-cabang';
                    if ($kategori === 'pusat') $badge = 'badge-pusat';
                    if ($kategori === 'outlet') $badge = 'badge-outlet';
                    if ($kategori === 'mitra') $badge = 'badge-mitra';
                    $alamat = $lokasi['alamat'] ?? '';
                    $coord = (!empty($lokasi['latitude']) && !empty($lokasi['longitude']))
                        ? $lokasi['latitude'] . ', ' . $lokasi['longitude']
                        : 'Belum diset';
                    ?>
                    <tr>
                        <td><span class="badge <?= $badge; ?>"><?= htmlspecialchars($lokasi['kategori']); ?></span></td>
                        <td>
                            <div style="font-weight:700;"><?= htmlspecialchars($lokasi['nama']); ?></div>
                            <div style="font-size:12px; color:#777; max-width:260px; white-space:normal; line-height:1.35;">
                                <?= htmlspecialchars(strlen($alamat) > 80 ? substr($alamat, 0, 80) . '...' : $alamat); ?>
                            </div>
                        </td>
                        <td>
                            <?php if(!empty($lokasi['username_admin'])): ?>
                                <div style="color:green; font-weight:700; font-size:13px;">User: <?= htmlspecialchars($lokasi['username_admin']); ?></div>
                            <?php else: ?>
                                <div style="color:#b71c1c; font-size:12px; font-weight:700;">Akun belum aktif</div>
                            <?php endif; ?>
                            <div style="font-size:12px; color:#555; margin-top:4px;"><i class="ph-bold ph-phone"></i> <?= htmlspecialchars($lokasi['telepon'] ?? '-'); ?></div>
                        </td>
                        <td style="font-size:12px; font-family:monospace; color:#0066cc;"><?= htmlspecialchars($coord); ?></td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <a href="<?= BASEURL; ?>/owner/lokasi/form/<?= $lokasi['id']; ?>" class="btn btn-edit" title="Edit">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <form action="<?= BASEURL; ?>/owner/lokasi/hapus/<?= $lokasi['id']; ?>" method="POST" class="form-hapus-lokasi">
                                    <button type="submit" class="btn btn-del" title="Hapus">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.form-hapus-lokasi').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus lokasi?',
                text: 'Data lokasi yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
