<?php 
session_start();
include 'koneksi.php'; 

// Cek Login Mitra (Satpam)
if (!isset($_SESSION['mitra_login'])) {
    header("Location: login.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Stok Gudang - Mitra DFC</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fff3e0; padding: 20px; margin: 0; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        .header { text-align: center; border-bottom: 2px solid #ff9800; padding-bottom: 15px; margin-bottom: 15px; }
        h2 { margin: 0; color: #e65100; font-size: 22px; }
        
        /* Indikator Status Koneksi */
        .connection-status {
            font-size: 11px; color: #666; margin-top: 5px;
            display: flex; justify-content: center; align-items: center; gap: 5px;
        }
        .dot-status { height: 8px; width: 8px; background-color: #ccc; border-radius: 50%; display: inline-block; }
        .online { background-color: #4caf50; box-shadow: 0 0 5px #4caf50; animation: pulse 1.5s infinite; }
        
        /* Item List Style */
        .item { display: flex; justify-content: space-between; padding: 15px 5px; border-bottom: 1px solid #f1f1f1; align-items: center; }
        
        .qty { 
            background: #e3f2fd; color: #0d47a1; 
            padding: 6px 14px; border-radius: 20px; 
            font-weight: bold; font-size: 15px;
            transition: all 0.3s ease;
            border: 1px solid #bbdefb;
        }
        
        /* Warna Stok Menipis */
        .habis { background: #ffebee; color: #c62828; border-color: #ffcdd2; }
        
        /* Efek Kedip saat update */
        .updated { background-color: #fff176 !important; transform: scale(1.1); } 

        .btn-wa { 
            display: block; background: #25d366; color: white; text-align: center; 
            padding: 12px; text-decoration: none; border-radius: 8px; 
            margin-top: 25px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-wa:hover { background: #1ebe57; }

        .btn-logout {
            display: block; text-align: center; margin-top: 15px; color: #d32f2f; 
            text-decoration: none; font-size: 13px; font-weight: 600; padding: 10px;
            border: 1px solid #ffebee; border-radius: 8px; background: #fffafa;
        }
        .btn-logout:hover { background: #ffebee; }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>STOK GUDANG PUSAT</h2>
            <div class="connection-status">
                <span class="dot-status" id="status-dot"></span>
                <span id="status-text">Menghubungkan...</span>
            </div>
        </div>

        <?php
        // Ambil Data Stok (Diurutkan Nama Barang A-Z, tanpa Kategori)
        $query = mysqli_query($conn, "SELECT * FROM stok_gudang ORDER BY nama_barang ASC");
        
        while($row = mysqli_fetch_assoc($query)) {
            $stok = $row['stok'];
            $class = ($stok <= 10) ? 'habis' : '';
        ?>
        <div class="item">
            <div>
                <strong style="color:#333; font-size:15px;"><?php echo $row['nama_barang']; ?></strong><br>
                <small style="color:#888;"><?php echo $row['satuan']; ?></small>
            </div>
            
            <div id="stok-<?php echo $row['id']; ?>" class="qty <?php echo $class; ?>">
                <?php echo number_format($stok); ?>
            </div>
        </div>
        <?php } ?>

        <a href="https://wa.me/6281222453572?text=Halo%20Admin%20Gudang,%20saya%20mitra%20mau%20restock%20barang..." class="btn-wa">
            Pesan Barang via WhatsApp
        </a>
        
        <a href="logout.php" class="btn-logout">
            Keluar (Logout)
        </a>
    </div>

    <script>
        function updateStok() {
            // Fetch data live
            fetch('cek-stok.php?t=' + new Date().getTime())
            .then(response => {
                if (!response.ok) throw new Error("Gagal");
                return response.json();
            })
            .then(data => {
                document.getElementById('status-dot').classList.add('online');
                document.getElementById('status-text').innerText = "Live Update Aktif";

                data.forEach(item => {
                    const elemenStok = document.getElementById('stok-' + item.id);
                    if (elemenStok) {
                        const stokBaru = parseInt(item.stok);
                        const stokLama = parseInt(elemenStok.innerText.replace(/\./g, '')); 

                        if (stokBaru !== stokLama) {
                            console.log("Update: " + stokLama + " -> " + stokBaru);
                            elemenStok.innerText = stokBaru.toLocaleString('id-ID');
                            
                            // Efek Kedip
                            elemenStok.classList.add('updated');
                            setTimeout(() => elemenStok.classList.remove('updated'), 500);

                            // Warna Merah jika stok sedikit
                            if (stokBaru <= 10) {
                                elemenStok.classList.add('habis');
                            } else {
                                elemenStok.classList.remove('habis');
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error("Error:", err);
                document.getElementById('status-dot').classList.remove('online');
                document.getElementById('status-text').innerText = "Terputus. Mencoba lagi...";
                document.getElementById('status-text').style.color = "red";
            });
        }

        setInterval(updateStok, 1000);
    </script>
</body>
</html>