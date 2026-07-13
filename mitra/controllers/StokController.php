<?php
require_once __DIR__ . '/../models/StokModel.php';

/**
 * Class StokController
 * Mengurus halaman dashboard stok gudang untuk mitra,
 * serta endpoint AJAX untuk live update.
 */
class StokController extends Controller
{
    private StokModel $stokModel;

    public function __construct()
    {
        $this->stokModel = new StokModel();
    }

    /**
     * Halaman utama: daftar stok gudang (butuh login).
     */
    public function index(): void
    {
        $this->requireLogin();

        $daftarStok = $this->stokModel->getAllOrderedByName();

        $this->view('stok/index', [
            'daftarStok' => $daftarStok,
            'namaMitra'  => $_SESSION['nama_mitra'] ?? 'Mitra',
        ]);
    }

    /**
     * Endpoint AJAX (dipanggil setInterval dari JS) -> balikan JSON.
     * Tetap dicek login supaya endpoint tidak bisa diakses sembarangan.
     */
    public function cekStok(): void
    {
        $this->requireLoginJson();

        $data = $this->stokModel->getAllStokRingkas();

        $this->json($data);
    }

    /**
     * Versi requireLogin khusus endpoint JSON:
     * kalau belum login, balikan 401 JSON, bukan redirect HTML.
     */
    private function requireLoginJson(): void
    {
        if (!isset($_SESSION['mitra_login'])) {
            http_response_code(401);
            $this->json(['error' => 'Unauthorized']);
        }
    }
}
