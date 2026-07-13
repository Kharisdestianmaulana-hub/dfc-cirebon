<?php
/**
 * Class MitraModel
 * Mengurus semua query terkait tabel akun_mitra.
 */
class MitraModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Cari akun mitra berdasarkan username.
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM akun_mitra WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);

        $data = $stmt->fetch();

        return $data ?: null;
    }
}
