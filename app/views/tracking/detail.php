<?php
$status = $data['pesanan']['status_pesanan'];
$step1 = $step2 = $step3 = $step4 = "";

if($status == 'Menunggu Konfirmasi') { $step1 = "active"; }
elseif($status == 'Sedang Disiapkan') { $step1 = "done"; $step2 = "active"; }
elseif($status == 'Siap Diambil') { $step1 = "done"; $step2 = "done"; $step3 = "active"; }
elseif($status == 'Selesai') { $step1 = "done"; $step2 = "done"; $step3 = "done"; $step4 = "active"; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fd020f" />
    <title><?= $data['judul']; ?></title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php if($status != 'Selesai'): ?>
    <meta http-equiv="refresh" content="5;url=<?= BASEURL; ?>/tracking/detail?id=<?= urlencode($data['order_id']); ?>&source=<?= urlencode($data['source']); ?>&phone_key=<?= urlencode($data['phone_key']); ?>">
    <?php endif; ?>
    
    <style>
        body { background: #f9f9f9; font-family: sans-serif; }
        .track-container { max-width: 500px; margin: 30px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px;}
        .header h2 { margin: 0 0 5px 0; color: #333; }
        .header p { margin: 0; color: #888; font-size: 14px; }
        .order-id { background: #ffebee; color: #fd020f; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-family: monospace; font-size: 16px; margin-top: 10px; display: inline-block;}

        /* CSS TIMELINE TRACKING */
        .timeline { position: relative; padding-left: 30px; margin-top: 30px; }
        .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 3px; background: #eee; }
        
        .step { position: relative; padding-bottom: 40px; }
        .step:last-child { padding-bottom: 0; }
        
        .step .icon { position: absolute; left: -36px; top: 0; width: 32px; height: 32px; border-radius: 50%; background: #eee; color: #aaa; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 4px solid white; z-index: 2; transition: 0.3s;}
        .step h4 { margin: 0; font-size: 15px; color: #aaa; }
        .step p { margin: 5px 0 0; font-size: 12px; color: #bbb; }

        /* Status Aktif */
        .step.active .icon { background: #ff9800; color: white; box-shadow: 0 0 0 4px #ffe0b2; animation: pulse 2s infinite; }
        .step.active h4 { color: #ff9800; font-weight: bold; }
        .step.active p { color: #666; }

        /* Status Selesai Dilewati */
        .step.done .icon { background: #4caf50; color: white; }
        .step.done h4 { color: #4caf50; }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 152, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0); }
        }

        .btn-wa { display: block; width: 100%; text-align: center; background: #25D366; color: white; padding: 15px; border-radius: 8px; font-weight: bold; text-decoration: none; margin-top: 30px; font-size: 16px; transition: 0.2s;}
        .btn-wa:hover { background: #1ebe57; }
        
        /* Gaya tombol kembali dinamis */
        .btn-kembali-dinamis { background: none; border: none; color: #666; font-size: 14px; cursor: pointer; padding: 0; text-decoration: none; font-family: inherit;}
        .btn-kembali-dinamis:hover { color: #fd020f; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="track-container">
        <div class="header">
            <h2>Status Pesanan</h2>
            <p>Atas Nama: <b><?= htmlspecialchars($data['pesanan']['nama_pemesan']); ?></b></p>
            <div class="order-id">#<?= htmlspecialchars($data['order_id']); ?></div>
            
            <?php if($status != 'Selesai'): ?>
                <p style="margin-top:10px; font-size: 12px; color: #fd020f;"><i class="fa-solid fa-rotate"></i> Halaman ini akan otomatis refresh.</p>
            <?php else: ?>
                <p style="margin-top:10px; font-size: 12px; color: #4CAF50;"><i class="fa-solid fa-check"></i> Pesanan telah selesai.</p>
            <?php endif; ?>
        </div>

        <div class="timeline">
            <div class="step <?= $step1; ?>">
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <h4>Menunggu Konfirmasi</h4>
                <p>Pesanan Anda telah diterima sistem dan menunggu konfirmasi Kasir Outlet.</p>
            </div>
            
            <div class="step <?= $step2; ?>">
                <div class="icon"><i class="fas fa-fire-burner"></i></div>
                <h4>Sedang Disiapkan</h4>
                <p>Ayam Anda sedang disiapkan dan dikemas. Harap bersabar ya!</p>
            </div>
            
            <div class="step <?= $step3; ?>">
                <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                <h4>Siap Diambil</h4>
                <p>Yeay! Makanan Anda sudah siap. Silakan ambil di kasir tanpa perlu antre.</p>
            </div>

            <div class="step <?= $step4; ?>">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <h4>Selesai</h4>
                <p>Terima kasih telah memesan DFC! Selamat menikmati.</p>
            </div>
        </div>

        <a href="https://wa.me/6281222453572?text=Halo%20Admin,%20saya%20mau%20tanya%20pesanan%20dengan%20ID%20<?= urlencode($data['order_id']); ?>" target="_blank" class="btn-wa">
            <i class="fab fa-whatsapp"></i> Chat Admin Outlet
        </a>
        
        <?php if($status == 'Selesai'): ?>
            <a href="<?= BASEURL; ?>/pesanan/struk?id=<?= urlencode($data['order_id']); ?>" target="_blank" style="display: block; width: 100%; text-align: center; background: #333; color: white; padding: 15px; border-radius: 8px; font-weight: bold; text-decoration: none; margin-top: 15px; font-size: 16px; transition: 0.2s;">
                <i class="fa-solid fa-receipt"></i> Cetak Struk Pembelian
            </a>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 25px;">
            <?php if($data['source'] == 'riwayat' && !empty($data['phone_key'])): ?>
                <form action="<?= BASEURL; ?>/tracking/riwayat" method="POST" style="display:inline;">
                    <input type="hidden" name="no_hp" value="<?= htmlspecialchars($data['phone_key']); ?>">
                    <input type="hidden" name="dari_tracking" value="1">
                    <button type="submit" class="btn-kembali-dinamis">&larr; Kembali ke Hasil Riwayat</button>
                </form>
            <?php else: ?>
                <a href="<?= BASEURL; ?>" class="btn-kembali-dinamis">&larr; Kembali ke Beranda</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
