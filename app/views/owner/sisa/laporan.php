<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-money" style="color:#ff0000;"></i> Laporan Keuangan</h2></div>

    <div class="native-filter">
        <form method="GET" action="<?= BASEURL; ?>/owner/laporan-full">
            <div>
                <select name="bulan"><?php foreach($data['list_bulan'] as $k=>$v): ?><option value="<?= $k; ?>" <?= $data['bulan']==$k?'selected':''; ?>><?= $v; ?></option><?php endforeach; ?></select>
            </div>
            <div>
                <input type="number" name="tahun" value="<?= htmlspecialchars($data['tahun']); ?>">
            </div>
            <button class="btn btn-add" type="submit"><i class="ph-bold ph-funnel"></i> Filter Data</button>
        </form>
    </div>

    <?php $totalBersih = 0; ?>
    <div class="card">
        <table class="native-table">
            <thead><tr><th>Tanggal</th><th>Outlet</th><th>Omzet Kotor</th><th>Pengeluaran</th><th>Bersih</th><th>Catatan</th></tr></thead>
            <tbody>
                <?php if(empty($data['laporan'])): ?>
                    <tr><td colspan="6" class="native-empty">Tidak ada data laporan di periode ini.</td></tr>
                <?php endif; ?>
                <?php foreach($data['laporan'] as $l): $totalBersih += (int)$l['omzet_bersih']; ?>
                    <tr><td><?= date('d M Y', strtotime($l['tanggal'])); ?></td><td><b><?= htmlspecialchars($l['kode_outlet']); ?></b></td><td>Rp <?= number_format($l['omzet_kotor']); ?></td><td>Rp <?= number_format($l['pengeluaran']); ?></td><td><b>Rp <?= number_format($l['omzet_bersih']); ?></b></td><td><?= htmlspecialchars($l['catatan']); ?></td></tr>
                <?php endforeach; ?>
                <tr class="native-total-row"><td colspan="4" style="text-align:right;">TOTAL BERSIH:</td><td colspan="2" style="color:#1b7f2a;">Rp <?= number_format($totalBersih); ?></td></tr>
            </tbody>
        </table>
    </div>
</div>
