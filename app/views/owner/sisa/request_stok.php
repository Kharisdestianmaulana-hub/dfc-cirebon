<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-bell-ringing" style="color:#ff0000;"></i> Permintaan Stok Outlet</h2></div>
    <?php if(!empty($data['flash'])): ?><div class="alert-owner"><?= htmlspecialchars($data['flash']); ?></div><?php endif; ?>
    <div class="card">
        <table class="native-table" style="min-width:1000px;"><thead><tr><th>Waktu Request</th><th>Nama Outlet</th><th>Barang Diminta</th><th>Status</th><th>Catatan / Alasan</th><th>Aksi Owner</th></tr></thead><tbody>
            <?php foreach($data['requests'] as $r): ?>
                <tr>
                    <td><?= date('d M Y, H:i', strtotime($r['waktu_request'] ?? 'now')); ?></td>
                    <td><b><?= htmlspecialchars($r['nama_outlet']); ?></b></td>
                    <td style="color:#1565c0; font-weight:800;"><?= number_format($r['jumlah']); ?> <?= htmlspecialchars($r['satuan']); ?> <?= htmlspecialchars($r['nama_barang']); ?></td>
                    <td><span class="native-status <?= $r['status']==='Disetujui'?'status-disetujui':($r['status']==='Ditolak'?'status-ditolak':'status-pending'); ?>"><?= htmlspecialchars($r['status']); ?></span></td>
                    <td><?= htmlspecialchars($r['alasan_tolak'] ?? '-'); ?></td>
                    <td>
                        <?php if($r['status'] === 'Pending'): ?>
                            <form method="POST" action="<?= BASEURL; ?>/owner/request-stok/approve" style="display:inline;"><input type="hidden" name="id_request" value="<?= $r['id']; ?>"><button class="btn btn-edit">Setujui</button></form>
                            <form method="POST" action="<?= BASEURL; ?>/owner/request-stok/tolak" style="display:inline-flex; gap:6px;"><input type="hidden" name="id_request" value="<?= $r['id']; ?>"><input type="text" name="alasan" placeholder="Alasan" style="width:120px; padding:8px; border:1px solid #ddd; border-radius:6px;"><button class="btn btn-del">Tolak</button></form>
                        <?php else: ?><span style="color:#999;"><i class="ph-bold ph-check-circle"></i> Selesai diproses</span><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
</div>
