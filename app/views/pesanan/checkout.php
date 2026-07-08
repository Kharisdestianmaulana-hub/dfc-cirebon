<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fd020f" />
    <title><?= $data['judul']; ?></title>
    <link rel="icon" type="image/jpg" href="<?= BASEURL; ?>/assets/img/Logodexpress (2).jpg">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f4f4; padding-bottom: 50px; font-family: sans-serif; margin:0;}
        .checkout-container { max-width: 600px; margin: 20px auto; padding: 0 15px; }
        .box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .box h3 { margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .order-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .item-left { flex: 1; }
        .item-note { font-size: 11px; color: #888; font-style: italic; display: block; }
        .item-price { font-weight: bold; }
        .total-row { display: flex; justify-content: space-between; margin-top: 15px; border-top: 2px dashed #ddd; padding-top: 15px; font-size: 18px; font-weight: bold; color: #fd020f; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        .payment-option { display: flex; gap: 15px; }
        .radio-label { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; text-align: center; transition: 0.2s; background: #fff; }
        .radio-label:hover { border-color: #fd020f; }
        input[type="radio"]:checked + .radio-label { background: #ffebee; border-color: #fd020f; color: #fd020f; font-weight: bold; }
        input[type="radio"] { display: none; } 
        .btn-confirm { width: 100%; padding: 15px; background: #25D366; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .btn-confirm:hover { background: #1ebe57; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div style="background: #fd020f; padding: 15px; text-align: center; color: white; font-weight: bold;">
        <i class="fas fa-shopping-bag"></i> Konfirmasi Pesanan
    </div>

    <div class="checkout-container">
        <div class="box">
            <h3><i class="fas fa-receipt"></i> Detail Pesanan (<?= $data['outlet_name']; ?>)</h3>
            <?php 
                $total_bayar = 0;
                foreach($data['cart'] as $item): 
                $subtotal = $item['harga'] * $item['qty'];
                $total_bayar += $subtotal;
            ?>
            <div class="order-item">
                <div class="item-left">
                    <b><?= $item['nama']; ?></b> x <?= $item['qty']; ?>
                    <?php if(!empty($item['catatan'])): ?>
                        <span class="item-note">Note: <?= $item['catatan']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="item-price">Rp <?= number_format($subtotal,0,',','.'); ?></div>
            </div>
            <?php endforeach; ?>
            <div class="total-row">
                <span>Total Bayar</span>
                <span>Rp <?= number_format($total_bayar,0,',','.'); ?></span>
            </div>
        </div>

        <div class="box">
            <h3><i class="fas fa-user"></i> Data & Pembayaran</h3>
            <form action="<?= BASEURL; ?>/pesanan/proses_checkout" method="POST">
                <input type="hidden" name="cart_data" value='<?= $data['cart_json']; ?>'>
                <input type="hidden" name="outlet_name" value="<?= $data['outlet_name']; ?>">
                <input type="hidden" name="outlet_phone" value="<?= $data['outlet_phone']; ?>">
                <input type="hidden" name="total_final" value="<?= $total_bayar; ?>">

                <div class="form-group">
                    <label>Nama Pemesan</label>
                    <input type="text" name="cust_name" placeholder="Contoh: Budi" required>
                </div>
                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="tel" name="cust_phone" placeholder="Contoh: 0812xxxx" required>
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <div class="payment-option">
                        <input type="radio" name="payment" id="pay-cash" value="Cash" checked>
                        <label for="pay-cash" class="radio-label">
                            <i class="fas fa-money-bill-wave"></i><br>Tunai (Cash)
                        </label>
                        <input type="radio" name="payment" id="pay-qris" value="QRIS">
                        <label for="pay-qris" class="radio-label">
                            <i class="fas fa-qrcode"></i><br>QRIS
                        </label>
                    </div>
                </div>
                <button type="submit" name="proses_pesanan" class="btn-confirm">
                    <i class="fas fa-check-circle"></i> PESAN SEKARANG
                </button>
            </form>
        </div>
        <a href="<?= BASEURL; ?>/pesanan" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Menu</a>
    </div>
</body>
</html>
