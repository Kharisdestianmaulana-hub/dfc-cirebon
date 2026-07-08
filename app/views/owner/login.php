<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #343a40; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 12px; text-align: center; width: 320px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;}
        button:hover { background: #c0392b; }
        h2 { margin-top: 0; color: #333; margin-bottom: 20px; font-size: 20px; }
        .logo-icon { font-size: 60px; color: #e74c3c; margin-bottom: 10px; display: inline-block;}
        .error { color:red; margin-top:15px; font-size:14px; background:#ffebee; padding:10px; border-radius:8px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="logo-icon"><i class="ph-fill ph-crown"></i></div>
        <h2>Ruang Owner DFC</h2>
        <form method="POST" action="<?= BASEURL; ?>/owner/login">
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit"><i class="ph-bold ph-sign-in"></i> MASUK DASHBOARD</button>
        </form>
        <?php if(!empty($data['error'])): ?>
            <p class="error"><i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($data['error']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
