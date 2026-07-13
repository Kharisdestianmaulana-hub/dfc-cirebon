<?php
/**
 * Class StokModel
 * Mengurus semua query terkait tabel stok_gudang.
 */
class StokModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Ambil semua data stok, diurutkan berdasarkan nama barang (A-Z).
     * Dipakai untuk render halaman utama.
     */
    public function getAllOrderedByName(): array
    {
        $stmt = $this->db->query("SELECT * FROM stok_gudang ORDER BY nama_barang ASC");
        return $stmt->fetchAll();
    }

    /**
     * Ambil ringkas id + stok saja.
     * Dipakai untuk endpoint live update (AJAX polling).
     */
    public function getAllStokRingkas(): array
    {
        $stmt = $this->db->query("SELECT id, stok FROM stok_gudang");
        return $stmt->fetchAll();
    }
}
