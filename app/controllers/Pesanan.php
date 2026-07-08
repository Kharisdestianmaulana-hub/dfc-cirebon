<?php

class Pesanan extends Controller {
    
    // 1. HALAMAN DAFTAR MENU (pesanan.php)
    public function index() {
        $data['judul'] = 'Pesan - DFC Cirebon';
        
        $model = $this->model('Pesanan_model');
        $data['menu'] = $model->getAllMenu();
        $data['outlets'] = $model->getOutlets();
        $data['status_toko'] = $model->getStatusToko();
        
        $list_outlet = [];
        $outlets_all = $model->getAllOutletUsernames();
        foreach($outlets_all as $o) {
            $list_outlet[] = $o['username_admin'];
        }
        $data['list_outlet_usernames'] = $list_outlet;

        $this->view('templates/header', $data);
        $this->view('pesanan/index', $data);
        $this->view('templates/footer');
    }

    public function status_outlet() {
        header('Content-Type: application/json');
        echo json_encode($this->model('Pesanan_model')->getStatusOutletDetail());
    }

    // 2. HALAMAN POST DATA CART & PILIH ALAMAT (checkout.php)
    public function checkout() {
        if(!isset($_POST['cart_data'])) {
            header("Location: " . BASEURL . "/pesanan");
            exit;
        }

        $data['judul'] = 'Checkout - Dexpress';
        $data['cart_json'] = $_POST['cart_data'];
        $data['cart'] = json_decode($data['cart_json'], true);
        $data['outlet_name'] = htmlspecialchars($_POST['outlet_name']);
        $data['outlet_phone'] = htmlspecialchars($_POST['outlet_phone']);
        
        $this->view('pesanan/checkout', $data);
    }

    // 3. PROSES SIMPAN KE DATABASE
    public function proses_checkout() {
        if(isset($_POST['proses_pesanan'])) {
            $model = $this->model('Pesanan_model');
            
            $nama = $_POST['cust_name'];
            $phone = $_POST['cust_phone'];
            $payment = $_POST['payment'];
            $total_final = $_POST['total_final'];
            $detail_pesanan = $_POST['cart_data']; 
            $outlet_name = $_POST['outlet_name'];

            $kode_outlet_dipilih = $model->getKodeOutletByName($outlet_name);
            $order_id = "DFC-" . date('dmy') . "-" . strtoupper(substr(uniqid(), -4));

            $orderData = [
                'order_id' => $order_id,
                'nama' => $nama,
                'phone' => $phone,
                'kode_outlet' => $kode_outlet_dipilih,
                'total_final' => $total_final,
                'payment' => $payment,
                'detail' => $detail_pesanan
            ];

            if($model->createOrder($orderData)) {
                if($payment == 'QRIS') {
                    header("Location: " . BASEURL . "/pesanan/qris_payment/" . $order_id);
                } else {
                    header("Location: " . BASEURL . "/tracking/detail?id=" . urlencode($order_id));
                }
                exit;
            } else {
                echo "Gagal memproses pesanan.";
                exit;
            }
        } else {
            header("Location: " . BASEURL . "/pesanan");
            exit;
        }
    }

    // 4. HALAMAN QRIS (qris-payment.php)
    public function qris_payment($order_id = '') {
        if(empty($order_id)) {
            $order_id = $_GET['id'] ?? '';
        }
        
        if(empty($order_id)) {
            die("ID Pesanan tidak valid.");
        }

        $model = $this->model('Pesanan_model');
        $data['pesanan'] = $model->getOrderById($order_id);

        if(!$data['pesanan']) {
            die("Pesanan tidak ditemukan.");
        }

        $data['judul'] = 'Pembayaran QRIS - ' . $order_id;
        $this->view('pesanan/qris_payment', $data);
    }

    // 5. HALAMAN CETAK STRUK (cetak-struk.php)
    public function struk($order_id = '') {
        if(empty($order_id)) {
            $order_id = $_GET['id'] ?? '';
        }

        if(empty($order_id)) {
            die("Data tidak ditemukan.");
        }

        $model = $this->model('Pesanan_model');
        $data['pesanan'] = $model->getOrderById($order_id);

        if(!$data['pesanan']) {
            die("Pesanan tidak valid.");
        }

        $data['judul'] = 'Struk Pembelian #' . $order_id;
        $data['items'] = json_decode($data['pesanan']['detail_pesanan'], true);
        
        $this->view('pesanan/cetak_struk', $data);
    }
}
