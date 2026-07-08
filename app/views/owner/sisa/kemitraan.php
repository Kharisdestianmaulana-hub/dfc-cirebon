<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-handshake" style="color:#ff0000;"></i> Pengajuan Mitra Baru</h2></div>
    <?php if(!empty($data['flash'])): ?><div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div><?php endif; ?>
    <div class="card">
        <table class="native-table" style="min-width:850px;"><thead><tr><th>No</th><th>Tanggal Masuk</th><th>Nama Lengkap</th><th>Lokasi Usaha</th><th>Pesan</th><th>Aksi</th></tr></thead><tbody>
            <?php $no=1; foreach($data['pengajuan'] as $p): ?>
                <tr>
                    <td><?= $no++; ?></td><td><?= isset($p['tanggal']) ? date('d M Y', strtotime($p['tanggal'])) : '-'; ?></td><td><b><?= htmlspecialchars($p['nama']); ?></b></td><td><?= htmlspecialchars($p['lokasi_usaha']); ?></td><td><?= htmlspecialchars(strlen($p['pesan']) > 55 ? substr($p['pesan'], 0, 55) . '...' : $p['pesan']); ?></td>
                    <td style="display:flex; gap:8px;"><a class="btn btn-wa" target="_blank" href="https://wa.me/<?= htmlspecialchars($p['whatsapp']); ?>"><i class="ph-fill ph-whatsapp-logo"></i> Hubungi</a><form method="POST" action="<?= BASEURL; ?>/owner/kemitraan/hapus/<?= $p['id']; ?>" class="form-confirm"><button class="btn btn-del"><i class="ph-bold ph-trash"></i></button></form></td>
                </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
</div>
