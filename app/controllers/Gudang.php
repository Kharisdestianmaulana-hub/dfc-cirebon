<?php

class Gudang extends Controller {
    
    public function __construct() {
        // Mulai session jika belum
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. HALAMAN INDEX (Daftar Stok)
    public function index() {
        // Cek login
        if (!isset($_SESSION['isGudangLoggedIn'])) {
            header("Location: " . BASEURL . "/gudang/login");
            exit;
        }

        $data['judul'] = 'Gudang Pusat - Manajemen Stok';
        $data['stok'] = $this->model('Gudang_model')->getAllStok();

        $this->view('gudang/templates/header', $data);
        $this->view('gudang/index', $data);
        $this->view('gudang/templates/footer');
    }

    // 2. HALAMAN LOGIN
    public function login() {
        // Jika sudah login, lempar ke index
        if (isset($_SESSION['isGudangLoggedIn'])) {
            header("Location: " . BASEURL . "/gudang");
            exit;
        }

        $data['judul'] = 'Login Gudang Pusat';

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->model('Gudang_model')->cekLogin($username);

            // Jika username ditemukan dan password cocok
            if ($user && $password == $user['password']) {
                $_SESSION['isGudangLoggedIn'] = true;
                $_SESSION['nama_admin_gudang'] = $user['nama_lengkap'];
                $_SESSION['id_gudang'] = $user['id'];
                
                header("Location: " . BASEURL . "/gudang");
                exit;
            } else {
                $data['error'] = "Username atau Password salah!";
            }
        }

        $this->view('gudang/templates/header', $data);
        $this->view('gudang/login', $data);
        $this->view('gudang/templates/footer');
    }

    // 3. PROSES UPDATE STOK
    public function update() {
        if (!isset($_SESSION['isGudangLoggedIn'])) {
            header("Location: " . BASEURL . "/gudang/login");
            exit;
        }

        if (isset($_POST['update_stok'])) {
            $id = $_POST['id'];
            $stok_baru = $_POST['stok'];

            $stok_lama_data = $this->model('Gudang_model')->getStokById($id);
            $nama_barang = $stok_lama_data['nama_barang'];
            $stok_lama = $stok_lama_data['stok'];
            
            $selisih = $stok_baru - $stok_lama;

            if ($selisih != 0) {
                // Update stok di tabel stok_gudang
                $this->model('Gudang_model')->updateStok($id, $stok_baru);
                
                // Catat ke tabel riwayat_stok
                $dataRiwayat = [
                    'nama_barang' => $nama_barang,
                    'stok_lama' => $stok_lama,
                    'stok_baru' => $stok_baru,
                    'selisih' => $selisih,
                    'admin' => $_SESSION['nama_admin_gudang']
                ];
                $this->model('Gudang_model')->catatRiwayat($dataRiwayat);
                
                // Gunakan Flasher agar alert bisa muncul setelah redirect (opsional, tapi pakai alert script JS saja biar gampang)
                echo "<script>alert('Sukses! Stok Berhasil Diupdate & Dicatat.'); window.location.href='".BASEURL."/gudang';</script>";
                exit;
            } else {
                header("Location: " . BASEURL . "/gudang");
                exit;
            }
        }
    }

    // 4. HALAMAN RIWAYAT
    public function riwayat() {
        if (!isset($_SESSION['isGudangLoggedIn'])) {
            header("Location: " . BASEURL . "/gudang/login");
            exit;
        }

        $data['judul'] = 'Riwayat Perubahan Stok Gudang';
        $data['riwayat'] = $this->model('Gudang_model')->getAllRiwayat();

        $this->view('gudang/templates/header', $data);
        $this->view('gudang/riwayat', $data);
        $this->view('gudang/templates/footer');
    }

    // 5. PROSES LOGOUT
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['isGudangLoggedIn']);
        unset($_SESSION['nama_admin_gudang']);
        unset($_SESSION['id_gudang']);
        
        header("Location: " . BASEURL . "/gudang/login");
        exit;
    }
}
