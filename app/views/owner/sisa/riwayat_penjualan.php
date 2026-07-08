<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-receipt" style="color:#ff0000;"></i> Riwayat Penjualan</h2></div>
    <div class="native-filter">
        <form method="GET" action="<?= BASEURL; ?>/owner/riwayat-penjualan" style="grid-template-columns: repeat(4, minmax(0,1fr));">
            <div><label>Dari Tanggal</label><input type="date" name="tgl_mulai" value="<?= htmlspecialchars($data['tgl_mulai']); ?>"></div>
            <div><label>Sampai Tanggal</label><input type="date" name="tgl_akhir" value="<?= htmlspecialchars($data['tgl_akhir']); ?>"></div>
            <div><label>Pilih Outlet</label><select name="outlet"><option value="semua">-- Semua Outlet --</option><?php foreach($data['outlets'] as $o): ?><option value="<?= htmlspecialchars($o['username_admin']); ?>" <?= $data['filter_outlet']===$o['username_admin']?'selected':''; ?>><?= htmlspecialchars($o['nama']); ?></option><?php endforeach; ?></select></div>
            <button class="btn btn-add" type="submit"><i class="ph-bold ph-funnel"></i> Tampilkan</button>
        </form>
    </div>

    <div class="card"><table class="native-table" style="min-width:1000px;"><thead><tr><th>Waktu Pesan</th><th>Order ID & Pemesan</th><th>Outlet</th><th>Detail Rincian (JSON)</th><th>Status</th><th>Total Bayar</th></tr></thead><tbody>
        <?php $total=0; $count=0; foreach($data['orders'] as $o): $total += (int)$o['total_bayar']; $count++; ?><tr><td><?= date('d M Y, H:i', strtotime($o['waktu_pesan'])); ?></td><td><b><?= htmlspecialchars($o['order_id']); ?></b><br><?= htmlspecialchars($o['nama_pemesan']); ?><br><small><?= htmlspecialchars($o['no_hp']); ?></small></td><td><?= htmlspecialchars($o['kode_outlet']); ?></td><td><code style="font-size:11px; white-space:normal;"><?= htmlspecialchars(substr($o['detail_pesanan'] ?? '-', 0, 120)); ?></code></td><td><?= htmlspecialchars($o['status_pesanan']); ?></td><td><b>Rp <?= number_format($o['total_bayar']); ?></b></td></tr><?php endforeach; ?>
        <?php if($count === 0): ?><tr><td colspan="6" class="native-empty">Tidak ada transaksi pesanan web di periode ini.</td></tr><?php endif; ?>
        <tr class="native-total-row"><td colspan="4" style="text-align:right;">TOTAL TRANSAKSI: &nbsp; <?= $count; ?> Pesanan</td><td colspan="2" style="color:#ff0000;">Rp <?= number_format($total); ?><br><small style="color:#777; font-size:11px;">*Hanya dari pesanan Selesai</small></td></tr>
    </tbody></table></div>
</div>
