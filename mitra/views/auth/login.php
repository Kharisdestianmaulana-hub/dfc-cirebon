<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mitra DFC Cirebon - Portal Kemitraan</title>
    <meta name="description" content="Halaman login khusus Mitra Dexpress Fried Chicken (DFC) Cirebon. Kelola stok, pesanan, dan laporan kemitraan Anda di sini.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-top: 5px solid #ff0000;
            border-radius: 10px;
            max-width: 350px;
            margin: 0 auto;
        }
        .btn-dfc {
            background: #ff0000;
            border-color: #ff0000;
            color: #fff;
            font-weight: bold;
        }
        .btn-dfc:hover {
            background: #cc0000;
            border-color: #cc0000;
            color: #fff;
        }
        .link-daftar span { color: #ff0000; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="card login-card shadow-sm p-4">
        <div class="card-body text-center">
            <h2 class="mb-1">Mitra DFC 🍗</h2>
            <p class="text-muted small mb-4">Silakan login untuk pantau stok.</p>

            <?php if (!empty($pesanError)): ?>
                <div class="alert alert-danger py-2 small" role="alert">
                    <?= htmlspecialchars($pesanError) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASEURL ?>/index.php?page=login">
                <div class="mb-3 text-start">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                </div>
                <div class="mb-3 text-start">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-dfc w-100">MASUK</button>
            </form>

            <a href="<?= BASEURL ?>/kemitraan" class="link-daftar small text-decoration-none text-muted">
                Belum menjadi mitra? <span>Daftar di sini</span>
            </a>
        </div>
    </div>
</div>
</body>
</html>
