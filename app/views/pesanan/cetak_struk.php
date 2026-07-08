<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['judul']; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #fff; color: #000; font-size: 12px; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .struk { width: 100%; max-width: 300px; border: 1px solid #ddd; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; font-weight: bold; }
        .header p { margin: 5px 0 0; font-size: 11px; }
        .info { margin-bottom: 15px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .info table { width: 100%; font-size: 11px; }
        .info td { vertical-align: top; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th { text-align: left; border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
        .items td { padding: 5px 0; }
        .totals { margin-top: 15px; border-top: 1px dashed #000; padding-top: 10px; }
        .totals table { width: 100%; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        
        /* Tombol print disembunyikan saat dicetak ke kertas */
        .btn-print { display: block; width: 100%; padding: 10px; background: #000; color: #fff; text-align: center; text-decoration: none; font-family: sans-serif; font-weight: bold; margin-top: 20px; cursor: pointer; border: none; }
        @media print {
            body { padding: 0; background: none; }
            .struk { border: none; box-shadow: none; width: 100%; max-width: 100%; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="struk">
        <div class="header">
            <h2>DEXPRESS FRIED CHICKEN</h2>
            <p>Outlet: <?= strtoupper($data['pesanan']['kode_outlet']); ?></p>
        </div>

        <div class="info">
            <table>
                <tr><td width="35%">Order ID</td><td>: <?= $data['pesanan']['order_id']; ?></td></tr>
                <tr><td>Tanggal</td><td>: <?= date('d-m-Y H:i', strtotime($data['pesanan']['waktu_pesan'])); ?></td></tr>
                <tr><td>Pemesan</td><td>: <?= htmlspecialchars($data['pesanan']['nama_pemesan']); ?></td></tr>
                <tr><td>Pembayaran</td><td>: <?= strtoupper($data['pesanan']['metode_bayar']); ?></td></tr>
            </table>
        </div>

        <div class="items">
            <table>
                <tr>
                    <th width="15%">Qty</th>
                    <th width="50%">Menu</th>
                    <th width="35%" style="text-align: right;">Total</th>
                </tr>
                <?php 
                if(is_array($data['items'])) {
                    foreach($data['items'] as $item) { 
                        $subtotal = $item['qty'] * $item['harga'];
                ?>
                <tr>
                    <td><?= $item['qty']; ?>x</td>
                    <td>
                        <?= htmlspecialchars($item['nama']); ?>
                        <?php if(!empty($item['catatan'])) echo "<br><small style='font-style:italic;'>- {$item['catatan']}</small>"; ?>
                    </td>
                    <td style="text-align: right;"><?= number_format($subtotal,0,',','.'); ?></td>
                </tr>
                <?php 
                    } 
                } 
                ?>
            </table>
        </div>

        <div class="totals">
            <table>
                <tr>
                    <td>TOTAL BAYAR</td>
                    <td style="text-align: right;">Rp <?= number_format($data['pesanan']['total_bayar'],0,',','.'); ?></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Terima kasih atas pesanan Anda.<br>
            <i>Renyah, Gurih, dan Menguntungkan!</i>
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ CETAK STRUK</button>
    </div>

</body>
</html>
