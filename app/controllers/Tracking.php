<?php
class Tracking extends Controller {
    public function index() {
        // Redirect to riwayat by default
        header('Location: ' . BASEURL . '/tracking/riwayat');
        exit;
    }

    public function riwayat() {
        $data['judul'] = 'Cek Riwayat Pesanan - DFC Cirebon';
        $no_hp = "";
        $sudah_dicari = false;
        $data['pesanan_aktif'] = [];
        $data['pesanan_selesai'] = [];

        if(isset($_POST['cari']) || isset($_POST['dari_tracking'])) {
            $no_hp = $_POST['no_hp'];
            $sudah_dicari = true;
            
            $model = $this->model('Riwayat_model');
            $data['pesanan_aktif'] = $model->getPesananAktif($no_hp);
            $data['pesanan_selesai'] = $model->getPesananSelesai($no_hp);
        }
        
        $data['no_hp'] = $no_hp;
        $data['sudah_dicari'] = $sudah_dicari;

        $this->view('templates/header', $data);
        $this->view('tracking/riwayat', $data);
        $this->view('templates/footer');
    }

    public function detail() {
        $order_id = $_GET['id'] ?? '';
        $source = $_GET['source'] ?? '';
        $phone_key = $_GET['phone_key'] ?? '';

        if(empty($order_id)) {
            die("Order ID tidak ditemukan.");
        }

        $pesananModel = $this->model('Pesanan_model');
        $data['pesanan'] = $pesananModel->getOrderById($order_id);

        if(!$data['pesanan']) {
            die("Pesanan tidak valid.");
        }

        $data['judul'] = 'Lacak Pesanan - DFC';
        $data['order_id'] = $order_id;
        $data['source'] = $source;
        $data['phone_key'] = $phone_key;

        $this->view('tracking/detail', $data);
    }
}
