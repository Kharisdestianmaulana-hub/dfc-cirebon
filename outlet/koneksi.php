<?php
// Compatibility bridge for external scripts that still expect $conn/catatLog.
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/ActivityLog.php';

$conn = Database::connection(); // PDO instance
date_default_timezone_set('Asia/Jakarta');

function catatLog($koneksi, $pelaku, $aksi, $detail): void
{
    (new ActivityLog())->add($pelaku, $aksi, $detail);
}
