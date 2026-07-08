<?php
class Menu_model {
    private $table = 'menu';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllMenu() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY id DESC');
        return $this->db->resultSet();
    }
}
