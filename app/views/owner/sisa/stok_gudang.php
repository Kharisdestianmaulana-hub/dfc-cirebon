<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header">
        <h2><i class="ph-fill ph-package" style="color:#ff0000;"></i> Master Stok Gudang</h2>
        <button class="btn btn-add" onclick="document.getElementById('formTambahStok').style.display = document.getElementById('formTambahStok').style.display === 'none' ? 'block' : 'none';"><i class="ph-bold ph-plus"></i> Tambah</button>
    </div>
    <?php if(!empty($data['flash'])): ?><div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div><?php endif; ?>

    <div id="formTambahStok" class="native-filter owner-form" style="display:none;">
            <form method="POST" action="<?= BASEURL; ?>/owner/stok-gudang/tambah">
                <div><label>Nama Barang</label><input type="text" name="nama_barang" required></div>
                <div><label>Stok Awal</label><input type="number" name="stok" min="0" required></div>
                <div><label>Satuan</label><input type="text" name="satuan" placeholder="Kg / Pack / Liter" required></div>
                <button class="owner-btn owner-btn-primary" type="submit"><i class="ph-bold ph-plus"></i> Tambah</button>
            </form>
    </div>

    <div class="card">
        <table class="native-table"><thead><tr><th>No</th><th>Nama Barang</th><th>Sisa</th><th>Satuan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            <?php $no=1; foreach($data['stok'] as $item): ?>
                <tr>
                    <form method="POST" action="<?= BASEURL; ?>/owner/stok-gudang/edit">
                        <input type="hidden" name="id_barang" value="<?= $item['id']; ?>">
                        <td><?= $no++; ?></td>
                        <td><input type="text" name="nama_barang" value="<?= htmlspecialchars($item['nama_barang']); ?>" style="width:220px; border:none; font-weight:700;"></td>
                        <td><input type="number" name="stok" value="<?= $item['stok']; ?>" style="width:90px; border:none; font-weight:700;"></td>
                        <td><input type="text" name="satuan" value="<?= htmlspecialchars($item['satuan']); ?>" style="width:100px; border:none;"></td>
                        <td><span class="native-status <?= ((int)$item['stok'] < 10) ? 'status-kritis' : 'status-aman'; ?>"><i class="ph-bold ph-check"></i> <?= ((int)$item['stok'] < 10) ? 'Kritis' : 'Aman'; ?></span></td>
                        <td style="display:flex; gap:8px;">
                            <button class="btn btn-edit" type="submit"><i class="ph-bold ph-pencil-simple"></i> Edit</button>
                    </form>
                            <form method="POST" action="<?= BASEURL; ?>/owner/stok-gudang/hapus/<?= $item['id']; ?>" class="form-confirm">
                                <button class="btn btn-del" type="submit"><i class="ph-bold ph-trash"></i> Hapus</button>
                            </form>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>

    <div class="page-header" style="margin-top:35px;"><h2><i class="ph-fill ph-clock-counter-clockwise" style="color:#ff0000;"></i> Riwayat Perubahan Stok</h2></div>
    <div class="card">
        <table class="native-table"><thead><tr><th>Waktu Input</th><th>Oleh Admin</th><th>Nama Barang</th><th>Stok Awal</th><th>Stok Akhir</th><th>Perubahan</th></tr></thead><tbody>
            <?php foreach($data['riwayat'] as $r): ?>
                <?php $selisih = (int)$r['selisih']; ?>
                <tr><td><?= date('d M Y, H:i', strtotime($r['waktu'] ?? $r['tanggal'] ?? 'now')); ?></td><td><?= htmlspecialchars($r['admin']); ?></td><td><b><?= htmlspecialchars($r['nama_barang']); ?></b></td><td><?= $r['stok_lama']; ?></td><td><?= $r['stok_baru']; ?></td><td style="font-weight:800; color:<?= $selisih < 0 ? '#d32f2f' : '#2e7d32'; ?>;"><?= $selisih > 0 ? '+' : ''; ?><?= $selisih; ?></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
</div>
