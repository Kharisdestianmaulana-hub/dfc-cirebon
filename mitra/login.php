<?php
session_start();
include '../koneksi.php';
require_once dirname(__DIR__) . '/app/config/config.php';

// Jika user sudah login sebelumnya, langsung lempar ke index
if(isset($_SESSION['mitra_login'])) {
    header("Location: index.php");
    exit;
}

$pesan_error = "";

if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Cek Username di Database
    $q = mysqli_query($conn, "SELECT * FROM akun_mitra WHERE username='$user'");

    if(mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);

        // Cek Password (Verifikasi Hash)
        if(password_verify($pass, $data['password'])) {
            // Login Sukses -> Set Session
            $_SESSION['mitra_login'] = true;
            $_SESSION['nama_mitra'] = $data['nama_mitra'];

            // Redirect ke halaman utama
            header("Location: index.php");
            exit;
        } else {
            $pesan_error = "Password yang Anda masukkan salah!";
        }
    } else {
        $pesan_error = "Username tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mitra DFC Cirebon - Portal Kemitraan</title> 
    <meta name="description" content="Halaman login khusus Mitra Dexpress Fried Chicken (DFC) Cirebon. Kelola stok, pesanan, dan laporan kemitraan Anda di sini.">
    <style>
        body { background: #f4f7fc; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; margin: 0; flex-direction: column; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); width: 300px; text-align: center; border-top: 5px solid #ff0000; }
        h2 { margin-top: 0; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        
        button { width: 100%; padding: 12px; background: #ff0000; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; margin-bottom: 15px; transition: 0.2s;}
        button:hover { background: #cc0000; }
        
        .error { background: #ffcccc; color: red; padding: 10px; border-radius: 5px; font-size: 13px; margin-bottom: 15px; border: 1px solid red; width: 300px; box-sizing: border-box; text-align: center; }
        
        /* CSS Untuk Link Pendaftaran */
        .link-daftar { font-size: 13px; color: #666; text-decoration: none; display: block; margin-top: 10px; }
        .link-daftar span { color: #ff0000; font-weight: bold; }
        .link-daftar:hover span { text-decoration: underline; }
    </style>
</head>
<body>

    <?php if(!empty($pesan_error)) echo "<div class='error'>$pesan_error</div>"; ?>

    <div class="login-box">
        <h2>Mitra DFC 🍗</h2>
        <p style="color:#666; font-size:13px; margin-bottom:20px;">Silakan login untuk pantau stok.</p>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">MASUK</button>
        </form>

        <a href="<?= BASEURL; ?>/kemitraan" class="link-daftar">Belum menjadi mitra? <span>Daftar di sini</span></a>
    </div>
</body>
</html>
