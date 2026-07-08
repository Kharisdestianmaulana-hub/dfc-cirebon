<?php
include 'koneksi.php';
header('Content-Type: application/json');

$query = mysqli_query($conn, "SELECT id, stok FROM stok_gudang");

$data = [];
while($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data);
?>