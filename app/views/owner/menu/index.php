<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-hamburger" style="color:#ff0000;"></i> Daftar Menu DFC</h2>
    </div>

    <a href="<?= BASEURL; ?>/owner/menu/form" class="btn btn-add">
        <i class="ph-bold ph-plus"></i> Tambah Menu Baru
    </a>

    <?php if(!empty($data['flash'])): ?>
        <div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div>
    <?php endif; ?>

    <div class="card">
        <table class="native-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['menu'])): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; color:#999; padding:30px;">Belum ada data menu.</td>
                    </tr>
                <?php endif; ?>

                <?php $no = 1; foreach($data['menu'] as $menu): ?>
                    <?php
                    $fileName = basename($menu['gambar'] ?? '');
                    $localFile = dirname(__DIR__, 4) . '/assets/img/owner/' . $fileName;
                    $imgSrc = $fileName && is_file($localFile)
                        ? BASEURL . '/assets/img/owner/' . rawurlencode($fileName)
                        : 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22150%22%20height%3D%22150%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20150%20150%22%3E%3Crect%20width%3D%22150%22%20height%3D%22150%22%20fill%3D%22%23EEEEEE%22%2F%3E%3Ctext%20x%3D%2242%22%20y%3D%2280%22%20fill%3D%22%23AAAAAA%22%20font-size%3D%2213%22%20font-family%3D%22Arial%22%3ENo%20Image%3C%2Ftext%3E%3C%2Fsvg%3E';
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><img src="<?= $imgSrc; ?>" class="img-thumb" alt="<?= htmlspecialchars($menu['nama']); ?>"></td>
                        <td style="font-weight:700;"><?= htmlspecialchars($menu['nama']); ?></td>
                        <td>Rp <?= number_format($menu['harga']); ?></td>
                        <td>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <a href="<?= BASEURL; ?>/owner/menu/form/<?= $menu['id']; ?>" class="btn btn-edit">
                                    <i class="ph-bold ph-pencil-simple"></i> Edit
                                </a>
                                <form action="<?= BASEURL; ?>/owner/menu/hapus/<?= $menu['id']; ?>" method="POST" class="form-hapus-menu" style="display:inline;">
                                    <button type="submit" class="btn btn-del">
                                        <i class="ph-bold ph-trash"></i> Hapus
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
    document.querySelectorAll('.form-hapus-menu').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin hapus menu?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
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
