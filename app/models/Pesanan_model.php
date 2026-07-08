<?php
class Pesanan_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllMenu() {
        $this->db->query("SELECT * FROM menu");
        return $this->db->resultSet();
    }

    public function getOutlets() {
        $this->db->query("SELECT * FROM lokasi WHERE username_admin IS NOT NULL AND username_admin != '' AND kategori != 'Pusat' ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    public function getAllOutletUsernames() {
        $this->db->query("SELECT username_admin FROM lokasi WHERE username_admin IS NOT NULL AND username_admin != ''");
        return $this->db->resultSet();
    }

    public function getStatusToko() {
        $this->db->query("SELECT * FROM pengaturan_toko");
        $result = $this->db->resultSet();
        $status_toko = [];
        foreach($result as $st) {
            $status_toko[$st['kode_outlet']] = $st['status'];
        }
        return $status_toko;
    }

    public function getStatusOutletDetail() {
        $this->db->query("SELECT kode_outlet, nama_outlet, status FROM pengaturan_toko");
        $result = $this->db->resultSet();
        $status_outlet = [];

        foreach($result as $row) {
            $status_outlet[$row['kode_outlet']] = $row;
        }

        return $status_outlet;
    }

    public function getKodeOutletByName($outlet_name) {
        $this->db->query("SELECT username_admin FROM lokasi WHERE nama=:nama");
        $this->db->bind('nama', $outlet_name);
        $result = $this->db->single();
        
        if($result) {
            return $result['username_admin'];
        } else {
            return strtolower(str_replace(' ', '', $outlet_name)); 
        }
    }

    public function createOrder($data) {
        $query = "INSERT INTO transaksi_pesanan (order_id, nama_pemesan, no_hp, kode_outlet, total_bayar, metode_bayar, detail_pesanan) 
                  VALUES (:order_id, :nama, :phone, :kode_outlet, :total_final, :payment, :detail)";
        $this->db->query($query);
        $this->db->bind('order_id', $data['order_id']);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('phone', $data['phone']);
        $this->db->bind('kode_outlet', $data['kode_outlet']);
        $this->db->bind('total_final', $data['total_final']);
        $this->db->bind('payment', $data['payment']);
        $this->db->bind('detail', $data['detail']);

        return $this->db->execute();
    }

    public function getOrderById($order_id) {
        $this->db->query("SELECT * FROM transaksi_pesanan WHERE order_id=:id");
        $this->db->bind('id', $order_id);
        return $this->db->single();
    }
}
