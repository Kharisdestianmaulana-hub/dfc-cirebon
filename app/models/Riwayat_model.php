<?php
class Riwayat_model {
    private $table = 'transaksi_pesanan';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPesananAktif($no_hp) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE no_hp=:no_hp AND status_pesanan != 'Selesai' ORDER BY waktu_pesan DESC");
        $this->db->bind('no_hp', $no_hp);
        return $this->db->resultSet();
    }

    public function getPesananSelesai($no_hp) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE no_hp=:no_hp AND status_pesanan = 'Selesai' ORDER BY waktu_pesan DESC");
        $this->db->bind('no_hp', $no_hp);
        return $this->db->resultSet();
    }
}
