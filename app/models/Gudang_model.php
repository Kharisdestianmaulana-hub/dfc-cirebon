<?php

class Gudang_model {
    private $table_stok = 'stok_gudang';
    private $table_riwayat = 'riwayat_stok';
    private $table_admin = 'admin_gudang';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // 1. Cek Login Admin Gudang
    // Catatan: Karena sistem owner menyimpan password dalam plain text,
    // kita membandingkan langsung tanpa password_verify (sesuai sistem native lama).
    public function cekLogin($username) {
        $this->db->query('SELECT * FROM ' . $this->table_admin . ' WHERE username = :username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    // 2. Ambil Semua Data Stok (Urut Abjad)
    public function getAllStok() {
        $this->db->query('SELECT * FROM ' . $this->table_stok . ' ORDER BY nama_barang ASC');
        return $this->db->resultSet();
    }

    // 3. Ambil Stok Berdasarkan ID
    public function getStokById($id) {
        $this->db->query('SELECT * FROM ' . $this->table_stok . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // 4. Update Stok
    public function updateStok($id, $stok_baru) {
        $this->db->query('UPDATE ' . $this->table_stok . ' SET stok = :stok_baru WHERE id = :id');
        $this->db->bind('stok_baru', $stok_baru);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    // 5. Catat ke Tabel Riwayat
    public function catatRiwayat($data) {
        $query = "INSERT INTO " . $this->table_riwayat . " 
                  (nama_barang, stok_lama, stok_baru, selisih, admin) 
                  VALUES (:nama_barang, :stok_lama, :stok_baru, :selisih, :admin)";
        
        $this->db->query($query);
        $this->db->bind('nama_barang', $data['nama_barang']);
        $this->db->bind('stok_lama', $data['stok_lama']);
        $this->db->bind('stok_baru', $data['stok_baru']);
        $this->db->bind('selisih', $data['selisih']);
        $this->db->bind('admin', $data['admin']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 6. Ambil Semua Riwayat (Terbaru di atas)
    public function getAllRiwayat() {
        $this->db->query('SELECT * FROM ' . $this->table_riwayat . ' ORDER BY waktu DESC');
        return $this->db->resultSet();
    }
}
