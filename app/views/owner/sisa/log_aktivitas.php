<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-eye" style="color:#ff0000;"></i> Log Aktivitas Karyawan</h2></div>
    <div class="card"><table class="native-table" style="min-width:850px;"><thead><tr><th>Waktu</th><th>Pelaku</th><th>Aksi</th><th>Detail Aktivitas</th></tr></thead><tbody>
        <?php foreach($data['logs'] as $log): ?>
            <?php
            $aksiLower = strtolower($log['aksi']);
            $class = 'log-login';
            $dot = '';
            if (strpos($aksiLower, 'stok') !== false || strpos($aksiLower, 'penjualan') !== false) { $class = 'log-stok'; $dot = 'orange'; }
            if (strpos($aksiLower, 'status') !== false) { $class = 'log-status'; $dot = 'orange'; }
            ?>
            <tr>
                <td><span class="log-time"><?= date('H:i:s', strtotime($log['waktu'])); ?></span><span class="log-date"><?= date('d M Y', strtotime($log['waktu'])); ?></span></td>
                <td><span class="log-dot <?= $dot; ?>"></span><b><?= htmlspecialchars(ucfirst($log['pelaku'])); ?></b></td>
                <td><span class="log-action <?= $class; ?>"><?= htmlspecialchars($log['aksi']); ?></span></td>
                <td><?= htmlspecialchars($log['detail']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody></table></div>
</div>
