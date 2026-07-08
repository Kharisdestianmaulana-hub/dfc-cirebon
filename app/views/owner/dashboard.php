<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="header-title">
        <h2>Halo, Bapak Owner! <i class="ph-fill ph-hand-waving" style="color:#ffcc00;"></i></h2>
        <p>Ini ringkasan bisnis Anda hari ini, <strong><?= $data['tanggal_lengkap']; ?></strong>.</p>
    </div>

    <div class="bento-grid">
        <div class="bento-card card-omzet <?= $data['card_class']; ?>">
            <div class="card-icon"><i class="ph-fill ph-wallet"></i></div>
            <div class="card-info"><h4>Omzet Hari Ini</h4></div>
            <div>
                <div class="angka">Rp <?= number_format($data['total_duit']); ?></div>
                <div class="card-info"><small><i class="ph-bold ph-trend-up"></i> Terupdate Realtime</small></div>
            </div>
        </div>

        <div class="bento-card">
            <div class="card-icon icon-yellow"><i class="ph-fill ph-warning-diamond"></i></div>
            <div class="card-info"><h4>Stok Kritis (&lt; 10)</h4></div>
            <div>
                <div class="angka <?= ($data['jml_kritis'] > 0) ? 'label-kritis' : ''; ?>"><?= $data['jml_kritis']; ?></div>
                <div class="card-info"><small>Item Perlu Restock</small></div>
            </div>
        </div>

        <div class="bento-card">
            <div class="card-icon icon-blue"><i class="ph-fill ph-shopping-cart"></i></div>
            <div class="card-info"><h4>Item Terjual</h4></div>
            <div>
                <div class="angka"><?= $data['total_item_terjual']; ?></div>
                <div class="card-info"><small>Pcs Hari Ini</small></div>
            </div>
        </div>

        <div class="bento-card">
            <div class="card-icon icon-purple"><i class="ph-fill ph-storefront"></i></div>
            <div class="card-info"><h4>Outlet Buka</h4></div>
            <div>
                <div class="angka"><?= $data['outlet_aktif']; ?></div>
                <div class="card-info"><small>Sedang Beroperasi</small></div>
            </div>
        </div>

        <div class="bento-card">
            <div class="card-icon icon-red"><i class="ph-fill ph-bell-ringing"></i></div>
            <div class="card-info"><h4>Request Stok</h4></div>
            <div>
                <div class="angka <?= ($data['request_pending'] > 0) ? 'label-kritis' : ''; ?>"><?= $data['request_pending']; ?></div>
                <div class="card-info"><small>Menunggu Persetujuan</small></div>
            </div>
        </div>
    </div>

    <div class="layout-bawah">
        <div class="box-wrapper box-chart">
            <div class="box-header">
                <h3><i class="ph-fill ph-chart-line-up" style="color:#1565c0;"></i> Trend Omzet 7 Hari Terakhir</h3>
            </div>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="omzetChart"></canvas>
            </div>
        </div>

        <div class="box-wrapper">
            <div class="box-header">
                <h3><i class="ph-fill ph-broadcast" style="color:#e60023;"></i> Aktivitas Terkini</h3>
            </div>

            <div class="timeline">
                <?php if(empty($data['timeline'])): ?>
                    <p style="text-align:center; padding:20px; color:#999; font-size:13px;">Belum ada aktivitas hari ini.</p>
                <?php else: ?>
                    <?php foreach($data['timeline'] as $item): ?>
                        <div class="timeline-item">
                            <div class="timeline-icon" style="background:<?= $item['bg']; ?>; color:<?= $item['color']; ?>;">
                                <i class="<?= $item['icon']; ?>"></i>
                            </div>
                            <div class="timeline-time"><i class="ph-fill ph-clock"></i> <?= substr($item['waktu'], 0, 5); ?> WIB</div>
                            <div class="timeline-content">
                                <span class="timeline-outlet"><?= htmlspecialchars($item['outlet']); ?></span>
                                <?= $item['teks']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="box-wrapper">
            <div class="box-header">
                <h3><i class="ph-fill ph-clipboard-text"></i> Laporan Keuangan Masuk</h3>
                <a href="<?= BASEURL; ?>/owner/laporan-full" style="text-decoration:none; color:#ff0000; font-size:12px; font-weight:bold;">Lihat Semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Outlet</th>
                        <th>Waktu</th>
                        <th>Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['laporan_terbaru'])): ?>
                        <tr><td colspan="3" style="text-align:center; padding:20px; color:#999;">Belum ada data.</td></tr>
                    <?php endif; ?>
                    <?php foreach($data['laporan_terbaru'] as $laporan): ?>
                        <?php
                        $outlet = ucfirst($laporan['kode_outlet']);
                        $badgeClass = (strpos(strtolower($outlet), 'mitra') !== false) ? 'badge-orange' : 'badge-blue';
                        ?>
                        <tr>
                            <td><span class="badge <?= $badgeClass; ?>"><?= htmlspecialchars($outlet); ?></span></td>
                            <td style="font-size:11px; color:#666;"><?= date('d/m', strtotime($laporan['tanggal'])) . ' ' . date('H:i', strtotime($laporan['waktu_input'])); ?></td>
                            <td style="font-weight:bold; color:#2e7d32;">Rp<?= number_format($laporan['omzet_bersih']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="box-wrapper">
            <div class="box-header">
                <h3><i class="ph-fill ph-fire" style="color:#ff9800;"></i> Terlaris Hari Ini</h3>
                <a href="<?= BASEURL; ?>/owner/riwayat-penjualan" style="text-decoration:none; color:#ff0000; font-size:12px; font-weight:bold;">Semua</a>
            </div>
            <ul class="top-menu-list">
                <?php if(empty($data['menu_terlaris'])): ?>
                    <p style="text-align:center; padding:20px; color:#999; font-size:13px;">Belum ada penjualan hari ini.</p>
                <?php endif; ?>
                <?php $rank = 1; foreach($data['menu_terlaris'] as $menu): ?>
                    <li class="top-menu-item">
                        <div style="display:flex; align-items:center;">
                            <div class="top-menu-rank <?= ($rank <= 3) ? 'rank-' . $rank : ''; ?>"><?= $rank; ?></div>
                            <div class="top-menu-name"><?= htmlspecialchars($menu['nama_menu']); ?></div>
                        </div>
                        <div class="top-menu-qty"><?= $menu['total_terjual']; ?> Pcs</div>
                    </li>
                    <?php $rank++; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    const labels = <?= json_encode($data['chart']['labels']); ?>;
    const dataOmzet = <?= json_encode($data['chart']['values']); ?>;
    const ctx = document.getElementById('omzetChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omzet Bersih (Rp)',
                data: dataOmzet,
                backgroundColor: 'rgba(253, 2, 15, 0.1)',
                borderColor: '#fd020f',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#fd020f',
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + ' Jt';
                            if (value >= 1000) return (value / 1000) + ' Rb';
                            return value;
                        }
                    }
                }
            }
        }
    });
</script>
