<?php

class Mitra extends Controller {

    public function __construct() {
        // Mulai session jika belum aktif
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. HALAMAN INDEX (Info Stok Gudang - Read Only untuk Mitra)
    public function index() {
        // Cek Login (Satpam)
        if (!isset($_SESSION['isMitraLoggedIn'])) {
            header("Location: " . BASEURL . "/mitra/login");
            exit;
        }

        $data['judul'] = 'Info Stok Gudang - Mitra DFC';
        $data['stok']  = $this->model('Mitra_model')->getAllStok();

        $this->view('mitra/templates/header', $data);
        $this->view('mitra/index', $data);
        $this->view('mitra/templates/footer');
    }

    // 2. HALAMAN LOGIN
    public function login() {
        // Jika sudah login, langsung lempar ke index
        if (isset($_SESSION['isMitraLoggedIn'])) {
            header("Location: " . BASEURL . "/mitra");
            exit;
        }

        $data['judul'] = 'Login Mitra DFC Cirebon';

        if (isset($_POST['login'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $akun = $this->model('Mitra_model')->cekLogin($username);

            // Verifikasi password menggunakan hash (bukan plain text)
            if ($akun && password_verify($password, $akun['password'])) {
                $_SESSION['isMitraLoggedIn'] = true;
                $_SESSION['nama_mitra']      = $akun['nama_mitra'];
                $_SESSION['id_mitra']        = $akun['id'];

                header("Location: " . BASEURL . "/mitra");
                exit;
            } elseif ($akun) {
                $data['error'] = "Password yang Anda masukkan salah!";
            } else {
                $data['error'] = "Username tidak terdaftar!";
            }
        }

        $this->view('mitra/templates/header', $data);
        $this->view('mitra/login', $data);
        $this->view('mitra/templates/footer');
    }

    // 3. ENDPOINT AJAX - CEK STOK LIVE (dipanggil via fetch() tiap 1 detik)
    // URL: /mitra/cek-stok  -> otomatis dipetakan App.php ke method cek_stok()
    public function cek_stok() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['isMitraLoggedIn'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $data = $this->model('Mitra_model')->getRingkasanStok();
        echo json_encode($data);
        exit;
    }

    // 4. PROSES LOGOUT
    public function logout() {
        unset($_SESSION['isMitraLoggedIn']);
        unset($_SESSION['nama_mitra']);
        unset($_SESSION['id_mitra']);

        header("Location: " . BASEURL . "/mitra/login");
        exit;
    }
}
