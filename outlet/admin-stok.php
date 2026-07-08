<?php
session_start();
include 'koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: admin-login.php");
    exit;
}

$kode_outlet = $_SESSION['kode_outlet']; 
$nama_outlet = $_SESSION['nama_outlet']; 

// SET TIMEZONE
date_default_timezone_set('Asia/Jakarta');

$swal_script = "";

// ==========================================
// --- LOGIKA 1: PROSES CATAT PENJUALAN ---
// ==========================================
if (isset($_POST['simpan_penjualan'])) {
    $id_menu = $_POST['id_menu'];
    $qty = $_POST['qty'];
    $catatan = $_POST['catatan_rasa']; 
    $tanggal = date('Y-m-d');
    $waktu = date('H:i:s');

    $q_menu = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id_menu'");
    $d_menu = mysqli_fetch_assoc($q_menu);
    $nama_menu = $d_menu['nama'];
    $harga = $d_menu['harga'];
    $subtotal = $harga * $qty;

    $sql_jual = "INSERT INTO penjualan_harian (tanggal, waktu, kode_outlet, nama_menu, qty, catatan, harga_satuan, subtotal) 
                 VALUES ('$tanggal', '$waktu', '$kode_outlet', '$nama_menu', '$qty', '$catatan', '$harga', '$subtotal')";
    
    if(mysqli_query($conn, $sql_jual)) {
        $kolom_stok = "stok_" . $kode_outlet;
        $sql_min_stok = "UPDATE menu SET $kolom_stok = $kolom_stok - $qty WHERE id='$id_menu'";
        mysqli_query($conn, $sql_min_stok);

        if(function_exists('catatLog')) {
            catatLog($conn, $kode_outlet, "Catat Penjualan", "Menjual $qty x $nama_menu. Note: $catatan");
        }

        $swal_script = "
            Swal.fire({
                title: 'Berhasil!',
                text: 'Penjualan dicatat & Stok berkurang!',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'admin-stok.php'; 
            });
        ";
    } else {
        $err = mysqli_error($conn);
        $swal_script = "Swal.fire('Gagal!', 'Gagal mencatat: $err', 'error');";
    }
}

// ==========================================
// --- LOGIKA 2: UBAH STATUS TOKO ---
// ==========================================
if (isset($_POST['ubah_status'])) {
    $status_baru = $_POST['status_toko'];
    $query = "UPDATE pengaturan_toko SET status='$status_baru' WHERE kode_outlet='$kode_outlet'";
    mysqli_query($conn, $query);
    
    if(function_exists('catatLog')) {
        catatLog($conn, $kode_outlet, "Ubah Status Toko", "Mengubah status toko menjadi " . strtoupper($status_baru));
    }
    
    $swal_script = "
        Swal.fire({
            title: 'Tersimpan!',
            text: 'Status outlet BERHASIL diubah!',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => { window.location.href='admin-stok.php'; });
    ";
}

// ==========================================
// --- LOGIKA 3: UPDATE STOK MANUAL ---
// ==========================================
if (isset($_POST['update_stok'])) {
    $id_menu = $_POST['id_menu'];
    $stok_baru = $_POST['stok_baru'];
    
    $q_n = mysqli_query($conn, "SELECT nama FROM menu WHERE id='$id_menu'");
    $d_n = mysqli_fetch_assoc($q_n);
    $nm_menu = $d_n['nama'];

    $nama_kolom_stok = "stok_" . $kode_outlet;
    $query_update = "UPDATE menu SET $nama_kolom_stok = '$stok_baru' WHERE id = '$id_menu'";
    mysqli_query($conn, $query_update);
    
    if(function_exists('catatLog')) {
        catatLog($conn, $kode_outlet, "Update Stok", "Ubah stok $nm_menu jadi $stok_baru");
    }

    $swal_script = "
        Swal.fire({
            title: 'Stok Diupdate!',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        }).then(() => { window.location.href='admin-stok.php'; });
    ";
}

// ==========================================
// --- LOGIKA 4: KIRIM REQUEST STOK KE PUSAT ---
// ==========================================
if (isset($_POST['kirim_request'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah = (int)$_POST['jumlah'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);

    $q_req = "INSERT INTO request_stok (nama_outlet, nama_barang, jumlah, satuan, status) 
              VALUES ('$nama_outlet', '$nama_barang', '$jumlah', '$satuan', 'Pending')";
    
    if (mysqli_query($conn, $q_req)) {
        $swal_script = "
            Swal.fire({
                title: 'Terkirim!',
                text: 'Permintaan restock sudah dikirim ke Gudang Pusat.',
                icon: 'success',
                confirmButtonColor: '#28a745'
            }).then(() => {
                window.location.href = 'admin-stok.php';
            });
        ";
    } else {
        $err = mysqli_error($conn);
        $swal_script = "Swal.fire('Gagal!', 'Gagal mengirim permintaan: $err', 'error');";
    }
}

// Ambil Status Toko Saat Ini
$query_status = mysqli_query($conn, "SELECT status FROM pengaturan_toko WHERE kode_outlet='$kode_outlet'");
$data_status = mysqli_fetch_assoc($query_status);
$status_sekarang = $data_status['status'] ?? 'tutup';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>Admin Panel - <?php echo $nama_outlet; ?></title>
    <link rel="stylesheet" href="style.css"> 
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { box-sizing: border-box; }
        body { background: #f4f7fc; font-family: 'Segoe UI', sans-serif; padding-bottom: 100px; margin: 0; overflow-x: hidden; }
        
        /* HEADER MERAH MODERN */
        header { background: #ff0000; color: white; padding: 15px 0; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .logo { font-size: 20px; font-weight: 800; display:flex; align-items:center; gap:8px;}

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        .status-box { background: white; padding: 25px; border-radius: 12px; margin-top: 25px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); text-align: center; border: 1px solid #eee;}
        .status-title { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:0; font-size: 22px; color:#333; }
        
        .section-menu { padding: 0; }
        .section-title { display:flex; align-items:center; gap:8px; margin-bottom:20px; color:#333; font-size:20px;}
        
        /* ========================================== */
        /* PERBAIKAN DESAIN CARD MENU (KOTAK AYAM)    */
        /* ========================================== */
        .menu-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
            gap: 20px; 
        }
        .menu-card { 
            background: white; 
            padding: 15px; 
            border-radius: 14px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
            text-align: center; 
            border: 1px solid #eaeaea;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }
        .menu-img { 
            width: 100%; 
            height: 140px; 
            object-fit: contain; /* Agar foto ayam tidak terpotong */
            border-radius: 10px; 
            margin-bottom: 15px; 
            background: #fafafa; /* Background abu muda agar rapi */
        }
        .menu-card h3 { 
            font-size: 16px; 
            margin: 0 0 15px 0; 
            color: #2d3436; 
            flex-grow: 1; /* Mendorong tombol selalu ke bawah */
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.3;
        }
        .card-actions { 
            display: flex; 
            flex-direction: column; 
            gap: 10px; /* Jarak antara input dan tombol */
        }
        .stok-input { 
            width: 100%; 
            text-align: center; 
            padding: 10px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: bold; 
            color: #333;
            background: #fafafa;
        }
        .stok-input:focus {
            outline: none;
            border-color: #ff0000;
            background: #fff;
        }
        .btn-primary { 
            width: 100%;
            background: #ff0000; 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            gap:6px;
            font-size: 14px;
            transition: 0.2s;
        }
        .btn-primary:hover { background: #d60000; }

        /* TOMBOL MELAYANG (FAB KANAN) - PENJUALAN */
        .fab-btn {
            position: fixed; bottom: 30px; right: 30px;
            background: #ff0000; color: white; width: 65px; height: 65px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 32px; 
            box-shadow: 0 5px 20px rgba(255,0,0,0.4); cursor: pointer; z-index: 990; border: none; transition: 0.3s;
        }

        /* TOMBOL MELAYANG (FAB KIRI) - REQUEST STOK */
        .fab-req-btn {
            position: fixed; bottom: 30px; left: 30px;
            background: #1565c0; color: white; width: 65px; height: 65px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 32px; 
            box-shadow: 0 5px 20px rgba(21, 101, 192, 0.4); cursor: pointer; z-index: 990; border: none; transition: 0.3s;
        }
        
        /* OVERLAY GELAP */
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; display: none; backdrop-filter: blur(3px); }
        .overlay.active { display: block; }

        /* --- SIDEBAR KANAN & KIRI (PANEL) --- */
        .side-panel, .side-panel-left {
            position: fixed; top: 0; width: 400px; height: 100vh; background: white;
            z-index: 1005; transition: 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            padding: 30px 20px; overflow-y: auto; box-sizing: border-box; 
        }
        
        .side-panel { right: -420px; box-shadow: -10px 0 30px rgba(0,0,0,0.1); }
        .side-panel.active { right: 0; }

        .side-panel-left { left: -420px; box-shadow: 10px 0 30px rgba(0,0,0,0.1); }
        .side-panel-left.active { left: 0; }
        
        .panel-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px; }
        .close-btn { background: #f4f7fc; border: none; width: 35px; height: 35px; border-radius: 50%; font-size: 20px; cursor: pointer; color: #333; display: flex; align-items: center; justify-content: center; transition: 0.2s;}
        .close-btn:hover { background: #fee; color: #ff0000; }

        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size:14px; }
        select, input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        select:focus, input:focus { border-color: #ff0000; outline: none; }
        
        .btn-simpan, .btn-kirim-req { 
            width: 100%; color: white; padding: 15px; border: none; border-radius: 8px; 
            font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; 
            display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-simpan { background: #28a745; }
        .btn-kirim-req { background: #1565c0; }

        .history-item { border-bottom: 1px solid #f0f0f0; padding: 12px 0; font-size: 14px; }
        .history-time { color: #888; font-size: 12px; margin-top: 5px; display:flex; align-items:center; gap:5px;}
        .req-status-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; }

        /* ========================================== */
        /* PERBAIKAN TAMPILAN KHUSUS HP (MOBILE)      */
        /* ========================================== */
        @media (max-width: 768px) {
            header { padding: 12px 0; }
            .nav-container { flex-direction: row; gap: 10px; justify-content: space-between; }
            .logo { font-size: 16px; }
            
            .nav-container nav { gap: 5px; }
            .nav-container nav a { padding: 6px 10px; font-size: 12px; border-radius: 4px; }
            .nav-container nav a i { font-size: 16px; }
            
            /* Penyesuaian Grid Menu di HP */
            .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .menu-card { padding: 12px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
            .menu-img { height: 100px; margin-bottom: 10px; }
            .menu-card h3 { font-size: 13px; min-height: 32px; margin-bottom: 12px; }
            .card-actions { gap: 8px; }
            .stok-input { padding: 8px; font-size: 14px; }
            .btn-primary { padding: 10px; font-size: 13px; }

            .side-panel, .side-panel-left { width: 100%; padding-top: 20px; } 
            .side-panel { right: -100%; }
            .side-panel-left { left: -100%; }
            
            .fab-btn, .fab-req-btn { width: 55px; height: 55px; font-size: 26px; bottom: 20px; }
            .fab-btn { right: 20px; }
            .fab-req-btn { left: 20px; }
        }
    </style>
</head>
<body>

    <div class="overlay" onclick="closeAllPanels()"></div>

    <header>
      <div class="container nav-container">
        <div class="logo">
            <i class="ph-fill ph-storefront"></i> Admin <?php echo ucfirst($kode_outlet); ?>
        </div>
        <nav style="display: flex; align-items: center;">
            <a href="pesanan-masuk.php" style="background: #ff9800; color: white; padding: 8px 12px; border-radius: 8px; font-weight:bold; text-decoration:none; display:flex; align-items:center; gap:5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <i class="ph-bold ph-bell-ringing"></i> <span class="hide-mobile">Pesanan Web</span>
            </a>
            <a href="laporan.php" style="background: white; color: #ff0000; font-weight:bold; text-decoration:none; display:flex; align-items:center; gap:5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <i class="ph-bold ph-file-text"></i> <span class="hide-mobile">Laporan</span>
            </a>
            <a href="logout.php" style="background: transparent; color: white; font-weight:bold; border:1px solid white; text-decoration:none; display:flex; align-items:center; gap:5px;">
                <i class="ph-bold ph-sign-out"></i> <span class="hide-mobile">Keluar</span>
            </a>
        </nav>
      </div>
    </header>

    <div class="container">
        <div class="status-box">
            <h2 class="status-title"><i class="ph-fill ph-sliders-horizontal" style="color:#ff0000;"></i> Panel Kendali: <span style="color: #ff0000;"><?php echo $nama_outlet; ?></span></h2>
            
            <?php 
                $color_status = ($status_sekarang == 'buka') ? '#28a745' : '#dc3545'; 
                $icon_status = ($status_sekarang == 'buka') ? 'ph-fill ph-check-circle' : 'ph-fill ph-x-circle';
                $text_status = ($status_sekarang == 'buka') ? 'BUKA' : 'TUTUP';
            ?>
            
            <div style="font-size:18px; margin-bottom: 20px; display:flex; align-items:center; justify-content:center; gap:8px;">
                Status Toko: 
                <span style="color: <?php echo $color_status; ?>; font-weight: 800; display:flex; align-items:center; gap:5px;">
                    <i class="<?php echo $icon_status; ?>" style="font-size:24px;"></i> <?php echo $text_status; ?>
                </span>
            </div>
            <form method="POST">
                <select name="status_toko" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; width: auto; display: inline-block;">
                    <option value="buka" <?php if($status_sekarang=='buka') echo 'selected'; ?>> Buka Toko</option>
                    <option value="tutup" <?php if($status_sekarang=='tutup') echo 'selected'; ?>> Tutup Toko</option>
                </select>
                <button type="submit" name="ubah_status" style="background: #ff0000; color: white; border: none; padding: 11px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; margin-left: 5px;"><i class="ph-bold ph-floppy-disk"></i> Simpan</button>
            </form>
        </div>

        <section class="section-menu">
            <h3 class="section-title"><i class="ph-fill ph-package" style="color:#ff0000;"></i> Stok Ayam Saat Ini</h3>
            <div class="menu-grid"> 
                <?php
                $query = mysqli_query($conn, "SELECT * FROM menu");
                while($row = mysqli_fetch_assoc($query)) {
                    $stok_tampil = $row['stok_' . $kode_outlet];
                    
                    $gambar_db = $row['gambar'];
                    $nama_file_saja = basename($gambar_db); 

                    if (!empty($nama_file_saja)) { 
                        $img_src = "https://dfc-cirebon.my.id/owner/img/" . $nama_file_saja; 
                    } else { 
                        $img_src = "https://via.placeholder.com/300?text=No+Image"; 
                    }
                ?>
                <div class="menu-card">
                    <img src="<?php echo $img_src; ?>" class="menu-img" onerror="this.src='https://via.placeholder.com/150?text=Error';"> 
                    <h3><?php echo $row['nama']; ?></h3>
                    <form method="POST" class="card-actions">
                        <input type="hidden" name="id_menu" value="<?php echo $row['id']; ?>">
                        <input type="number" name="stok_baru" class="stok-input" value="<?php echo $stok_tampil; ?>">
                        <button type="submit" name="update_stok" class="btn-primary"><i class="ph-bold ph-arrows-clockwise"></i> Update</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <button class="fab-btn" onclick="toggleSalesPanel()" title="Catat Penjualan Manual">
        <i class="ph-bold ph-note-pencil"></i>
    </button>

    <div class="side-panel" id="salesPanel">
        <div class="panel-header">
            <h3 style="margin:0; font-size:20px; color:#333; display:flex; align-items:center; gap:8px;"><i class="ph-fill ph-note-pencil" style="color:#ff0000; font-size:24px;"></i> Catat Penjualan</h3>
            <button class="close-btn" onclick="closeAllPanels()"><i class="ph-bold ph-x"></i></button>
        </div>
        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; font-size: 13px; color: #856404; margin-bottom: 20px; border-left: 4px solid #ffeeba; display:flex; gap:8px;">
            <i class="ph-fill ph-info" style="font-size:18px;"></i>
            <span>Input jika ada pesanan offline. Stok berkurang otomatis.</span>
        </div>
        <form method="POST">
            <div class="form-group">
                <label>Pilih Menu</label>
                <select name="id_menu" required>
                    <?php
                    mysqli_data_seek($query, 0); 
                    while($m = mysqli_fetch_assoc($query)) {
                        echo "<option value='".$m['id']."'>".$m['nama']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah (Qty)</label>
                <input type="number" name="qty" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Catatan / Detail Rasa</label>
                <input type="text" name="catatan_rasa" placeholder="Cth: 5 Pedas, 5 Original" required autocomplete="off">
            </div>
            <button type="submit" name="simpan_penjualan" class="btn-simpan"><i class="ph-bold ph-check-circle"></i> SIMPAN PENJUALAN</button>
        </form>

        <h4 style="margin-top: 40px; border-bottom: 2px solid #eee; padding-bottom: 10px; display:flex; align-items:center; gap:8px;">
            <i class="ph-fill ph-clock-counter-clockwise" style="color:#ff0000; font-size:20px;"></i> Riwayat Hari Ini (<?php echo date('d/m'); ?>)
        </h4>
        <div style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
            <?php
            $tgl_today = date('Y-m-d');
            $q_riwayat = mysqli_query($conn, "SELECT * FROM penjualan_harian WHERE kode_outlet='$kode_outlet' AND tanggal='$tgl_today' ORDER BY id DESC");
            if(mysqli_num_rows($q_riwayat) == 0) {
                echo "<p style='color:#999; font-size:13px; text-align:center; padding: 20px 0;'>Belum ada penjualan hari ini.</p>";
            }
            while($hist = mysqli_fetch_assoc($q_riwayat)) {
            ?>
            <div class="history-item">
                <div style="display:flex; justify-content:space-between; font-weight:bold; color:#333;">
                    <span><?php echo $hist['nama_menu']; ?> <span style="color:#ff0000;">(x<?php echo $hist['qty']; ?>)</span></span>
                    <span style="color:#28a745;">Rp <?php echo number_format($hist['subtotal']); ?></span>
                </div>
                <div class="history-time">
                    <i class="ph-fill ph-clock"></i> <?php echo substr($hist['waktu'], 0, 5); ?> WIB <br> 
                    <span style="color:#888; font-style:italic; display:flex; gap:5px; margin-top:3px;"><i class="ph-fill ph-chat-text" style="color:#ccc;"></i> <?php echo empty($hist['catatan']) ? '-' : $hist['catatan']; ?></span>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <button class="fab-req-btn" onclick="toggleReqPanel()" title="Minta Restock ke Pusat">
        <i class="ph-bold ph-truck"></i>
    </button>

    <div class="side-panel-left" id="reqPanel">
        <div class="panel-header">
            <h3 style="margin:0; font-size:20px; color:#333; display:flex; align-items:center; gap:8px;"><i class="ph-fill ph-truck" style="color:#1565c0; font-size:24px;"></i> Request Restock</h3>
            <button class="close-btn" onclick="closeAllPanels()"><i class="ph-bold ph-x"></i></button>
        </div>
        <div style="background: #e3f2fd; padding: 12px; border-radius: 8px; font-size: 13px; color: #1565c0; margin-bottom: 20px; border-left: 4px solid #90caf9; display:flex; gap:8px;">
            <i class="ph-fill ph-paper-plane-tilt" style="font-size:18px;"></i>
            <span>Kirim permintaan bahan baku ke Gudang Pusat.</span>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Bahan Baku</label>
                <input type="text" name="nama_barang" placeholder="Cth: Tepung Bumbu / Ayam" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <select name="satuan" required>
                    <option value="Pcs">Pcs</option>
                    <option value="Kg">Kg</option>
                    <option value="Liter">Liter</option>
                    <option value="Dus">Dus</option>
                    <option value="Pack">Pack</option>
                </select>
            </div>
            <button type="submit" name="kirim_request" class="btn-kirim-req"><i class="ph-bold ph-paper-plane-right"></i> KIRIM PERMINTAAN</button>
        </form>

        <h4 style="margin-top: 40px; border-bottom: 2px solid #eee; padding-bottom: 10px; display:flex; align-items:center; gap:8px;">
            <i class="ph-fill ph-clock-counter-clockwise" style="color:#1565c0; font-size:20px;"></i> Riwayat Request Terakhir
        </h4>
        
        <div id="history-request-container" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
            <p style='color:#999; font-size:13px; text-align:center; padding: 20px 0;'>Memuat riwayat...</p>
        </div>

    </div>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        const sPanel = document.getElementById("salesPanel");
        const rPanel = document.getElementById("reqPanel");
        const ov = document.querySelector(".overlay");

        function toggleSalesPanel() {
            rPanel.classList.remove("active"); 
            sPanel.classList.toggle("active");
            ov.classList.toggle("active");
            document.body.style.overflow = sPanel.classList.contains("active") ? "hidden" : "";
        }

        function toggleReqPanel() {
            sPanel.classList.remove("active"); 
            rPanel.classList.toggle("active");
            ov.classList.toggle("active");
            document.body.style.overflow = rPanel.classList.contains("active") ? "hidden" : "";
        }

        function closeAllPanels() {
            sPanel.classList.remove("active");
            rPanel.classList.remove("active");
            ov.classList.remove("active");
            document.body.style.overflow = ""; 
        }

        function loadHistoryRequest() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get-history-request.php', true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('history-request-container').innerHTML = this.responseText;
                }
            }
            xhr.send();
        }

        loadHistoryRequest();
        setInterval(loadHistoryRequest, 3000);

        <?php echo $swal_script; ?>
    </script>

</body>
</html>