<?php
class Profil_model {
    private $table = 'profil_perusahaan';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProfil() {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=1');
        return $this->db->single();
    }
}
