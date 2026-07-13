<?php
// ================================
// KONFIGURASI UMUM APLIKASI
// ================================

// --- Database ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'dfc_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// --- Aplikasi ---
define('BASEURL', 'http://localhost/mitra-dfc'); // sesuaikan dengan folder project Anda
define('APP_TIMEZONE', 'Asia/Jakarta');

date_default_timezone_set(APP_TIMEZONE);

// --- Nomor WhatsApp Admin Gudang ---
define('WA_ADMIN_GUDANG', '6281222453572');
