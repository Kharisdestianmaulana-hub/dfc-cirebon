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
function catatLog($koneksi, $pelaku, $aksi, $detail) {
    // Set waktu Indonesia
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('Y-m-d H:i:s');
    
    // Bersihkan input biar aman
    $pelaku = mysqli_real_escape_string($koneksi, $pelaku);
    $aksi = mysqli_real_escape_string($koneksi, $aksi);
    $detail = mysqli_real_escape_string($koneksi, $detail);
    
    // Simpan ke database
    $sql = "INSERT INTO log_aktivitas (waktu, pelaku, aksi, detail) VALUES ('$waktu', '$pelaku', '$aksi', '$detail')";
    mysqli_query($koneksi, $sql);
}
?>