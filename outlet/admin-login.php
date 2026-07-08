<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['isLoggedIn'])) {
    header("Location: admin-stok.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_input = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);

        if (password_verify($password_input, $data['password'])) {
            
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['kode_outlet'] = $username; 
            
            // --- BAGIAN INI YANG DIUPDATE JADI DINAMIS ---
            // Mengambil nama outlet (misal: "Cabang Majalengka") langsung dari database lokasi
            $q_nama = mysqli_query($conn, "SELECT nama FROM lokasi WHERE username_admin='$username'");
            $d_nama = mysqli_fetch_assoc($q_nama);

            if ($d_nama) {
                $_SESSION['nama_outlet'] = $d_nama['nama'];
            } else {
                $_SESSION['nama_outlet'] = "Outlet " . ucfirst($username); // Jaga-jaga kalau data lokasi terhapus
            }
            catatLog($conn, $username, "Login Masuk", "Admin $username berhasil login ke sistem.");

            header("Location: admin-stok.php");
            exit;
        }
    }

    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan Outlet DFC Cirebon</title>
    <meta name="description" content="Portal login untuk karyawan outlet dan kasir DFC Cirebon. Kelola pesanan masuk dan stok harian outlet.">
    <meta name="keywords" content="dfc outlet, login outlet dfc, kasir dfc cirebon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffebee; 
            display: flex; justify-content: center; align-items: center;
            height: 100vh; margin: 0;
        }
        .login-box {
            background: white; padding: 40px; border-radius: 15px;
            box-shadow: 0 10px 25px rgba(253, 2, 15, 0.15);
            width: 100%; max-width: 350px; text-align: center;
            border-top: 5px solid #fd020f; 
        }
        h2 { color: #fd020f; margin-bottom: 20px; font-weight: 800; }
        
        input {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 1px solid #ddd; border-radius: 50px; box-sizing: border-box;
            text-align: center; font-size: 14px;
        }
        input:focus { outline: none; border-color: #fd020f; background: #fffbfb; }

        button {
            width: 100%; padding: 12px; 
            background: linear-gradient(45deg, #fd020f, #c4000b);
            color: white; border: none; border-radius: 50px;
            font-size: 16px; cursor: pointer; font-weight: bold; margin-top: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        button:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(253, 2, 15, 0.3); }
        
        .error { 
            color: #d32f2f; font-size: 14px; margin-bottom: 15px; 
            background: #ffcdd2; padding: 10px; border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>LOGIN OUTLET</h2>
        <p style="font-size: 12px; color: #666; margin-bottom: 20px;">Silakan login sesuai nama cabang</p>

        <?php if(isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username (cth: sutawinangun)" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">MASUK</button>
        </form>
    </div>

</body>
</html>