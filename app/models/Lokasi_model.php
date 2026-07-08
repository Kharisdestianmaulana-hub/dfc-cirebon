<?php
class Lokasi_model {
    private $table = 'lokasi';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getLokasi() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY kategori = 'Pusat' DESC, kategori = 'Outlet' DESC, kategori = 'Mitra' DESC, nama ASC");
        return $this->db->resultSet();
    }
}
