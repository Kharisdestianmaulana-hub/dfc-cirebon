<?php /* View rendered by OrderController */ ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Web Masuk - <?php echo htmlspecialchars($nama_outlet); ?></title>
    <meta http-equiv="refresh" content="10"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7fc; color: #333; }
        
        /* HEADER MERAH OUTLET */
        header { background: #ff0000; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;}
        .back-btn { background: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
        .back-btn:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .page-title { display: flex; align-items: center; gap: 10px; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* KARTU PESANAN */
        .order-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 5px solid #ff9800; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;}
        .order-info { flex: 1; min-width: 300px; }
        .order-info h3 { margin: 0 0 10px 0; font-size: 18px; color: #333; display: flex; justify-content: space-between;}
        
        .order-info p { margin: 0 0 8px 0; font-size: 14px; color: #555; display: flex; align-items: center; gap: 8px; }
        .order-info p i { font-size: 18px; color: #8898aa; }
        
        .badge-status { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 10px; }
        .status-menunggu { background: #fff3e0; color: #e65100; }
        .status-disiapkan { background: #e3f2fd; color: #1565c0; }
        .status-siap { background: #e8f5e9; color: #2e7d32; }

        /* KOTAK DETAIL MENU (JSON) */
        .detail-box { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #eee; }
        .detail-box ul { margin: 0; padding-left: 20px; font-size: 14px; color: #333; }
        .detail-box li { margin-bottom: 5px; }
        .note-text { color: #e65100; font-size: 12px; font-style: italic; display: block; margin-top: 2px; }

        /* TOMBOL AKSI */
        .action-form { display: flex; flex-direction: column; gap: 10px; min-width: 150px; }
        .btn-action { padding: 12px 15px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; color: white; display: flex; align-items: center; justify-content: center; gap: 5px; transition: 0.2s; width: 100%;}
        .btn-proses { background: #2196F3; } .btn-proses:hover { background: #1976D2; }
        .btn-siap { background: #ff9800; } .btn-siap:hover { background: #f57c00; }
        .btn-selesai { background: #4CAF50; } .btn-selesai:hover { background: #388E3C; }
    </style>
</head>
<body>

    <header>
        <div style="font-size: 18px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
            <i class="ph-fill ph-bell-ringing"></i> Pesanan Web Masuk
        </div>
        <a href="admin-stok.php" class="back-btn"><i class="ph-bold ph-arrow-left"></i> Kembali</a>
    </header>

    <div class="container">
        <div class="page-title">
            <h2>Daftar Pesanan (Self-Pickup)</h2>
        </div>

        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; font-size: 13px; color: #856404; margin-bottom: 20px; border-left: 4px solid #ffeeba; display:flex; align-items: flex-start; gap:8px;">
            <i class="ph-fill ph-info" style="font-size:18px; margin-top: 2px;"></i>
            <span>Halaman ini akan refresh otomatis tiap 10 detik. Jika pesanan sudah Selesai, jangan lupa potong stok di halaman depan menggunakan tombol merah.</span>
        </div>

        <?php if (empty($orders)): ?>
            <div style="background:white; padding:40px; text-align:center; border-radius:12px; color:#888; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">Belum ada pesanan masuk saat ini.</div>
        <?php else: foreach ($orders as $order):
            $status = $order['status_pesanan'];
            $badge_class = "";
            if($status == 'Menunggu Konfirmasi') $badge_class = "status-menunggu";
            if($status == 'Sedang Disiapkan') $badge_class = "status-disiapkan";
            if($status == 'Siap Diambil') $badge_class = "status-siap";
        ?>
        
        <div class="order-card">
            <div class="order-info">
                <h3>#<?php echo $order['order_id']; ?> 
                    <span style="font-size: 13px; color: #888; font-weight: normal; display: flex; align-items: center; gap: 4px;">
                        <i class="ph-fill ph-clock" style="font-size: 16px;"></i> <?php echo date('H:i', strtotime($order['waktu_pesan'])); ?>
                    </span>
                </h3>
                
                <p><i class="ph-fill ph-user"></i> <b><?php echo htmlspecialchars($order['nama_pemesan']); ?></b> (<?php echo htmlspecialchars($order['no_hp']); ?>)</p>
                <p><i class="ph-fill ph-credit-card"></i> Pembayaran: <b><?php echo htmlspecialchars($order['metode_bayar']); ?></b></p>
                <p><i class="ph-fill ph-money" style="color: #ff0000;"></i> Total Bayar: <b style="color: #ff0000; font-size: 16px;">Rp <?php echo number_format($order['total_bayar'],0,',','.'); ?></b></p>
                
                <div class="detail-box">
                    <strong style="font-size:12px; color:#555; display:flex; align-items:center; gap:5px; margin-bottom: 8px;">
                        <i class="ph-fill ph-shopping-bag"></i> DETAIL PESANAN:
                    </strong>
                    <ul>
                        <?php 
                        $items = json_decode($order['detail_pesanan'], true);
                        if(is_array($items)) {
                            foreach($items as $item) {
                                echo "<li><b>{$item['qty']}x " . htmlspecialchars($item['nama']) . "</b>";
                                if(!empty($item['catatan'])) {
                                    echo " <span class='note-text'>Catatan: " . htmlspecialchars($item['catatan']) . "</span>";
                                }
                                echo "</li>";
                            }
                        } else {
                            echo "<li>Gagal memuat detail</li>";
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="badge-status <?php echo $badge_class; ?>"><?php echo $status; ?></div>
            </div>

            <div class="action-form">
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    
                    <?php if($status == 'Menunggu Konfirmasi'): ?>
                        <input type="hidden" name="status_baru" value="Sedang Disiapkan">
                        <button type="submit" name="update_status" class="btn-action btn-proses">
                            <i class="ph-bold ph-cooking-pot"></i> Terima & Masak
                        </button>
                    <?php elseif($status == 'Sedang Disiapkan'): ?>
                        <input type="hidden" name="status_baru" value="Siap Diambil">
                        <button type="submit" name="update_status" class="btn-action btn-siap">
                            <i class="ph-bold ph-shopping-bag"></i> Makanan Siap
                        </button>
                    <?php elseif($status == 'Siap Diambil'): ?>
                        <input type="hidden" name="status_baru" value="Selesai">
                        <button type="submit" name="update_status" class="btn-action btn-selesai">
                            <i class="ph-bold ph-check-circle"></i> Selesaikan
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php endforeach; endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
