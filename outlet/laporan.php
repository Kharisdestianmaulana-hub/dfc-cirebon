<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: admin-login.php");
    exit;
}

if (!isset($_SESSION['kode_outlet'])) {
    die("<h3 style='color:red;'>Error: Session 'kode_outlet' tidak ditemukan. Silakan Login ulang.</h3>");
}

$outlet = $_SESSION['kode_outlet'];
$nama_outlet = isset($_SESSION['nama_outlet']) ? $_SESSION['nama_outlet'] : 'Outlet DFC';

if (isset($_POST['simpan_laporan'])) {
    
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $kotor = mysqli_real_escape_string($conn, $_POST['omzet_kotor']);
    $keluar = mysqli_real_escape_string($conn, $_POST['pengeluaran']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    
    $bersih = $kotor - $keluar;

    $cek_query = "SELECT id FROM laporan_keuangan WHERE tanggal='$tanggal' AND kode_outlet='$outlet'";
    $cek = mysqli_query($conn, $cek_query);

    if ($cek) {
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE laporan_keuangan SET 
                      omzet_kotor='$kotor', 
                      pengeluaran='$keluar', 
                      omzet_bersih='$bersih', 
                      catatan='$catatan' 
                      WHERE tanggal='$tanggal' AND kode_outlet='$outlet'";
        } else {
            $query = "INSERT INTO laporan_keuangan (tanggal, kode_outlet, omzet_kotor, pengeluaran, omzet_bersih, catatan) 
                      VALUES ('$tanggal', '$outlet', '$kotor', '$keluar', '$bersih', '$catatan')";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Laporan Keuangan Berhasil Disimpan!'); window.location.href='laporan.php';</script>";
        }
    }
}

$labels = [];
$data_omzet = [];
$q_chart = mysqli_query($conn, "SELECT tanggal, omzet_bersih FROM laporan_keuangan WHERE kode_outlet='$outlet' ORDER BY tanggal DESC LIMIT 7");

if ($q_chart) {
    while ($d = mysqli_fetch_assoc($q_chart)) {
        array_unshift($labels, date('d M', strtotime($d['tanggal']))); 
        array_unshift($data_omzet, $d['omzet_bersih']);
    }
}

$json_labels = json_encode($labels);
$json_data = json_encode($data_omzet);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - <?php echo $nama_outlet; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h2 { margin: 0; color: #1a1a1a; display: flex; align-items: center; gap: 10px; }
        .btn-back { background: white; border: 1px solid #ccc; padding: 8px 15px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; display: flex; align-items: center; gap: 5px; }
        .grid-dashboard { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        @media(max-width: 768px) { .grid-dashboard { grid-template-columns: 1fr; } }
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-title { font-weight: bold; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-size: 13px; color: #666; font-weight: 500; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        input:focus { border-color: #fd020f; outline: none; }
        .btn-submit { width: 100%; background: #fd020f; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; }
        .btn-submit:hover { background: #c4000b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th { text-align: left; background: #f8f9fa; padding: 10px; color: #555; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .plus { color: green; font-weight: bold; }
        .icon { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .icon-sm { width: 16px; height: 16px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>
            <svg class="icon" style="color: #fd020f;" viewBox="0 0 24 24"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            Laporan Keuangan
        </h2>
        <a href="admin-stok.php" class="btn-back">
            <svg class="icon icon-sm" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>
    </div>

    <div class="grid-dashboard">
        
        <div class="card">
            <div class="card-title">
                <svg class="icon" style="color:#666;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Input Laporan Hari Ini
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Omzet (Kotor)</label>
                    <input type="number" name="omzet_kotor" placeholder="Contoh: 1500000" required>
                </div>
                <div class="form-group">
                    <label>Pengeluaran (Belanja/Lainnya)</label>
                    <input type="number" name="pengeluaran" placeholder="Contoh: 300000" value="0" required>
                </div>
                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Contoh: Beli gas, plastik habis"></textarea>
                </div>
                <button type="submit" name="simpan_laporan" class="btn-submit">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Laporan
                </button>
            </form>
        </div>

        <div class="main-content">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-title">
                    <svg class="icon" style="color:#666;" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Grafik Keuntungan Bersih (7 Hari Terakhir)
                </div>
                <canvas id="omzetChart" height="150"></canvas>
            </div>

            <div class="card">
                <div class="card-title">
                    <svg class="icon" style="color:#666;" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    Riwayat Laporan Terbaru
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kotor</th>
                            <th>Keluar</th>
                            <th>Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_tabel = mysqli_query($conn, "SELECT * FROM laporan_keuangan WHERE kode_outlet='$outlet' ORDER BY tanggal DESC LIMIT 5");
                        if ($q_tabel) {
                            while($r = mysqli_fetch_assoc($q_tabel)) {
                        ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($r['tanggal'])); ?></td>
                            <td><?php echo number_format($r['omzet_kotor']); ?></td>
                            <td style="color:red;">-<?php echo number_format($r['pengeluaran']); ?></td>
                            <td class="plus">Rp <?php echo number_format($r['omzet_bersih']); ?></td>
                        </tr>
                        <?php 
                            } 
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('omzetChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $json_labels; ?>,
            datasets: [{
                label: 'Keuntungan Bersih (Rp)',
                data: <?php echo $json_data; ?>,
                backgroundColor: 'rgba(253, 2, 15, 0.7)',
                borderColor: 'rgba(253, 2, 15, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

</body>
</html>