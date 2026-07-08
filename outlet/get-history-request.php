<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin outlet yang bisa akses
if (!isset($_SESSION['isLoggedIn'])) { exit; }

$nama_outlet = $_SESSION['nama_outlet'];

// Ambil 5 riwayat terakhir untuk outlet ini
$q_hist_req = mysqli_query($conn, "SELECT * FROM request_stok WHERE nama_outlet='$nama_outlet' ORDER BY id DESC LIMIT 5");

if(mysqli_num_rows($q_hist_req) == 0) {
    echo "<p style='color:#999; font-size:12px; text-align:center;'>Belum pernah request.</p>";
    exit;
}

while($hr = mysqli_fetch_assoc($q_hist_req)) {
    $stat = $hr['status'];
    if($stat == 'Pending') $c_stat = "background:#fff3e0; color:#e65100;";
    elseif($stat == 'Disetujui') $c_stat = "background:#e8f5e9; color:#2e7d32;";
    else $c_stat = "background:#ffebee; color:#c62828;";
?>
    <div class="history-item">
        <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
            <strong style="color:#1565c0;"><?php echo $hr['jumlah']." ".$hr['satuan']." ".$hr['nama_barang']; ?></strong>
            <span class="req-status-badge" style="<?php echo $c_stat; ?>"><?php echo $stat; ?></span>
        </div>
        <div class="history-time"><?php echo date('d M Y, H:i', strtotime($hr['waktu_request'])); ?></div>
        <?php if($stat == 'Ditolak'): ?>
            <div style="font-size:11px; color:#c62828; margin-top:3px; font-style:italic;">Alasan: <?php echo $hr['alasan_tolak']; ?></div>
        <?php endif; ?>
    </div>
<?php 
} 
?>