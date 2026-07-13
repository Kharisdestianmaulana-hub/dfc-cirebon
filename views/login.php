<?php /* View rendered by AuthController */ ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan Outlet DFC Cirebon</title>
    <meta name="description" content="Portal login untuk karyawan outlet dan kasir DFC Cirebon. Kelola pesanan masuk dan stok harian outlet.">
    <meta name="keywords" content="dfc outlet, login outlet dfc, kasir dfc cirebon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

        <?php if(isset($error)) { echo "<div class='alert alert-danger error' role='alert'>" . htmlspecialchars($error) . "</div>"; } ?>
        
        <form method="POST">
            <input class="form-control" type="text" name="username" placeholder="Username (cth: sutawinangun)" required autocomplete="off">
            <input class="form-control" type="password" name="password" placeholder="Password" required>
            <button class="btn btn-danger" type="submit" name="login">MASUK</button>
        </form>
    </div>

</body>
</html>
