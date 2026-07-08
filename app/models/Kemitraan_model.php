<?php
class Kemitraan_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function tambahPengajuan($data) {
        $this->db->query("INSERT INTO pengajuan_mitra (nama, whatsapp, lokasi_usaha, pesan) VALUES (:nama, :whatsapp, :lokasi, :pesan)");
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('whatsapp', $data['whatsapp']);
        $this->db->bind('lokasi', $data['lokasi']);
        $this->db->bind('pesan', $data['pesan']);

        return $this->db->execute();
    }
}
