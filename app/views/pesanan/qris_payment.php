<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <style>
        body { background: #f4f4f4; font-family: sans-serif; text-align: center; padding: 40px 20px; }
        .qris-box { background: white; max-width: 400px; margin: 0 auto; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h2 { color: #fd020f; margin-top: 0; }
        /* Gunakan gambar QRIS placeholder atau ganti src-nya dengan gambar QRIS asli */
        .img-qris { width: 100%; max-width: 250px; border: 2px solid #eee; border-radius: 10px; margin: 20px 0; }
        .btn-lanjut { display: block; background: #25D366; color: white; text-decoration: none; padding: 15px; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="qris-box">
        <h2>Pembayaran QRIS</h2>
        <p>Order ID: <b>#<?= htmlspecialchars($data['pesanan']['order_id']); ?></b></p>
        <p style="color:#666; font-size:14px;">Silakan scan kode QRIS di bawah ini menggunakan aplikasi M-Banking atau E-Wallet Anda (Dana, Ovo, Gopay, dll).</p>
        
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS DFC" class="img-qris">
        
        <a href="<?= BASEURL; ?>/tracking/detail?id=<?= urlencode($data['pesanan']['order_id']); ?>&source=checkout&phone_key=<?= urlencode($data['pesanan']['no_hp']); ?>" class="btn-lanjut">Saya Sudah Bayar &rarr;</a>
    </div>
</body>
</html>
