<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dfc_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta'); // Memaksa PHP pakai waktu WIB
mysqli_query($conn, "SET time_zone = '+07:00'");
?>