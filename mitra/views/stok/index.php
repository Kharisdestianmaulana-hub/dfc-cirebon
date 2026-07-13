<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Stok Gudang - Mitra DFC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #fff3e0; }
        .container-stok { max-width: 500px; }
        .card-stok { border-radius: 10px; }
        .header-stok { border-bottom: 2px solid #ff9800; }
        h2.title { color: #e65100; }

        .dot-status {
            height: 8px; width: 8px; background-color: #ccc;
            border-radius: 50%; display: inline-block;
        }
        .online { background-color: #4caf50; box-shadow: 0 0 5px #4caf50; animation: pulse 1.5s infinite; }

        .qty {
            background: #e3f2fd; color: #0d47a1;
            padding: 6px 14px; border-radius: 20px;
            font-weight: bold; font-size: 15px;
            transition: all 0.3s ease;
            border: 1px solid #bbdefb;
        }
        .habis { background: #ffebee; color: #c62828; border-color: #ffcdd2; }
        .updated { background-color: #fff176 !important; transform: scale(1.1); }

        .btn-wa { background: #25d366; color: #fff; font-weight: bold; }
        .btn-wa:hover { background: #1ebe57; color: #fff; }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="container container-stok py-4">
    <div class="card card-stok shadow-sm p-3">
        <div class="text-center header-stok pb-3 mb-3">
            <h2 class="title mb-1">STOK GUDANG PUSAT</h2>
            <div class="small text-muted d-flex justify-content-center align-items-center gap-2">
                <span class="dot-status" id="status-dot"></span>
                <span id="status-text">Menghubungkan...</span>
            </div>
            <div class="small text-muted mt-1">Halo, <?= htmlspecialchars($namaMitra) ?></div>
        </div>

        <?php foreach ($daftarStok as $row): ?>
            <?php $class = ($row['stok'] <= 10) ? 'habis' : ''; ?>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <strong class="d-block" style="color:#333; font-size:15px;">
                        <?= htmlspecialchars($row['nama_barang']) ?>
                    </strong>
                    <small class="text-muted"><?= htmlspecialchars($row['satuan']) ?></small>
                </div>

                <div id="stok-<?= (int) $row['id'] ?>" class="qty <?= $class ?>">
                    <?= number_format((int) $row['stok']) ?>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="https://wa.me/<?= WA_ADMIN_GUDANG ?>?text=Halo%20Admin%20Gudang,%20saya%20mitra%20mau%20restock%20barang..."
           class="btn btn-wa w-100 mt-4">
            Pesan Barang via WhatsApp
        </a>

        <a href="<?= BASEURL ?>/index.php?page=logout" class="btn btn-outline-danger w-100 mt-2">
            Keluar (Logout)
        </a>
    </div>
</div>

<script>
    const BASEURL = "<?= BASEURL ?>";

    function updateStok() {
        fetch(BASEURL + '/index.php?page=cek-stok&t=' + new Date().getTime())
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
                        elemenStok.innerText = stokBaru.toLocaleString('id-ID');

                        elemenStok.classList.add('updated');
                        setTimeout(() => elemenStok.classList.remove('updated'), 500);

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
            const statusText = document.getElementById('status-text');
            statusText.innerText = "Terputus. Mencoba lagi...";
            statusText.style.color = "red";
        });
    }

    setInterval(updateStok, 1000);
</script>
</body>
</html>
