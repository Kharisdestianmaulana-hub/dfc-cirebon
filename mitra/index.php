<?php
/**
 * FRONT CONTROLLER
 * Semua request masuk lewat file ini (index.php di root project),
 * lalu di-route ke controller yang sesuai berdasarkan ?page=
 *
 * Contoh route:
 *   index.php?page=login    -> AuthController::login
 *   index.php?page=logout   -> AuthController::logout
 *   index.php?page=stok     -> StokController::index
 *   index.php?page=cek-stok -> StokController::cekStok (JSON, dipanggil via AJAX)
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/StokController.php';

$page = $_GET['page'] ?? (isset($_SESSION['mitra_login']) ? 'stok' : 'login');

switch ($page) {
    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'stok':
        (new StokController())->index();
        break;

    case 'cek-stok':
        (new StokController())->cekStok();
        break;

    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan.";
        break;
}
