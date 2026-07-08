    <style>
        body { 
            background: #f9f9f9; 
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .hero-riwayat {
            background-color: #fd020f; 
            color: white;
            text-align: center;
            padding: 80px 20px 60px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            margin-top: 0;
        }
        .hero-riwayat h1 { margin: 0; font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .hero-riwayat p { margin: 10px 0 0 0; font-size: 1.1rem; opacity: 0.9; }

        .search-container { 
            max-width: 600px; 
            margin: -40px auto 40px; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            position: relative; 
            z-index: 10;
        }
        
        .form-group label { display: block; font-weight: bold; margin-bottom: 10px; color: #333; }
        .input-group { display: flex; gap: 10px; }
        .input-group input { flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; outline: none; font-family: 'Poppins', sans-serif;}
        .input-group input:focus { border-color: #fd020f; }
        .btn-search { background: #fd020f; color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif;}
        .btn-search:hover { background: #cc0000; }

        .result-container { max-width: 800px; margin: 0 auto 40px; padding: 0 20px; }
        .section-title { font-size: 1.2rem; font-weight: 700; color: #fd020f; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;}

        .order-card { background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 5px solid #ccc; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px;}
        .card-aktif { border-left-color: #ff9800; }
        .card-selesai { border-left-color: #4CAF50; opacity: 0.8; }
        
        .order-info h3 { margin: 0 0 5px 0; font-size: 16px; color: #333; }
        .order-info p { margin: 0 0 5px 0; font-size: 13px; color: #666; }
        
        .badge { display: inline-block; padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: bold; }
        .bg-warning { background: #fff3e0; color: #e65100; }
        .bg-success { background: #e8f5e9; color: #2e7d32; }

        .btn-track { background: #ff9800; color: white; border: none; padding: 10px 15px; border-radius: 8px; font-size: 13px; font-weight: bold; display: inline-block; cursor: pointer; transition: 0.2s; font-family: 'Poppins', sans-serif;}
        .btn-track:hover { background: #e65100; }
        
        /* Warna untuk tombol yang sudah selesai */
        .btn-track-success { background: #f0f0f0; color: #555; }
        .btn-track-success:hover { background: #e0e0e0; }

        .empty-state { text-align: center; padding: 40px; color: #888; background: white; border-radius: 12px; border: 1px dashed #ddd;}

        @media (max-width: 768px) {
            .input-group { flex-direction: column; }
            .btn-search { padding: 15px; }
            .order-card { flex-direction: column; align-items: flex-start; }
            .btn-track { width: 100%; text-align: center; }
        }
    </style>

    <section class="hero-riwayat">
        <h1>Cek Pesanan Anda</h1>
        <p>Pantau status pesanan atau lihat riwayat sebelumnya</p>
    </section>

    <div style="padding: 0 20px;">
        <div class="search-container">
            <form action="<?= BASEURL; ?>/tracking/riwayat" method="POST">
                <div class="form-group">
                    <label>Masukkan Nomor WhatsApp Anda</label>
                    <div class="input-group">
                        <input type="tel" name="no_hp" placeholder="Contoh: 0812xxxxxx" value="<?= htmlspecialchars($data['no_hp']); ?>" required>
                        <button type="submit" name="cari" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($data['sudah_dicari']): ?>
    <div class="result-container">
        
        <h3 class="section-title"><i class="fa-solid fa-fire-burner" style="color:#ff9800;"></i> Sedang Dipesan</h3>
        <?php if(count($data['pesanan_aktif']) > 0): ?>
            <?php foreach($data['pesanan_aktif'] as $p): ?>
                <div class="order-card card-aktif">
                    <div class="order-info">
                        <h3>#<?= $p['order_id']; ?> <span class="badge bg-warning"><?= $p['status_pesanan']; ?></span></h3>
                        <p><i class="fa-solid fa-store"></i> Outlet: <?= ucfirst($p['kode_outlet']); ?></p>
                        <p><i class="fa-solid fa-calendar-day"></i> <?= date('d M Y - H:i', strtotime($p['waktu_pesan'])); ?></p>
                        <p style="color: #fd020f; font-weight: bold; margin-top:5px;">Total: Rp <?= number_format($p['total_bayar'],0,',','.'); ?> (<?= $p['metode_bayar']; ?>)</p>
                    </div>
                    <div style="width: 100%; max-width: 200px;">
                        <!-- TODO: URL to Tracking page detailed view -->
                        <form action="<?= BASEURL; ?>/tracking/detail" method="GET" style="width: 100%;">
                            <input type="hidden" name="id" value="<?= $p['order_id']; ?>">
                            <input type="hidden" name="source" value="riwayat">
                            <input type="hidden" name="phone_key" value="<?= htmlspecialchars($data['no_hp']); ?>">
                            <button type="submit" class="btn-track" style="width: 100%;">Lacak Pesanan <i class="fa-solid fa-arrow-right"></i></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Tidak ada pesanan yang sedang diproses.</div>
        <?php endif; ?>


        <h3 class="section-title" style="margin-top: 40px; color: #4CAF50;"><i class="fa-solid fa-check-circle"></i> Riwayat Selesai</h3>
        <?php if(count($data['pesanan_selesai']) > 0): ?>
            <?php foreach($data['pesanan_selesai'] as $p): ?>
                <div class="order-card card-selesai">
                    <div class="order-info" style="flex:1;">
                        <h3 style="color:#555;">#<?= $p['order_id']; ?> <span class="badge bg-success">Selesai</span></h3>
                        <p><i class="fa-solid fa-store"></i> Outlet: <?= ucfirst($p['kode_outlet']); ?></p>
                        <p><i class="fa-solid fa-calendar-day"></i> <?= date('d M Y', strtotime($p['waktu_pesan'])); ?></p>
                        <p style="color: #555; font-weight: bold; margin-top:5px;">Total: Rp <?= number_format($p['total_bayar'],0,',','.'); ?></p>
                    </div>
                    
                    <div style="width: 100%; max-width: 200px; text-align: right;">
                        <form action="<?= BASEURL; ?>/pesanan/struk" method="GET" style="width: 100%;">
                            <input type="hidden" name="id" value="<?= $p['order_id']; ?>">
                            <button type="submit" class="btn-track btn-track-success" style="width: 100%;"><i class="fa-solid fa-eye"></i> Lihat Struk / Detail</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Belum ada riwayat pesanan yang selesai.</div>
        <?php endif; ?>

    </div>
    <?php endif; ?>
