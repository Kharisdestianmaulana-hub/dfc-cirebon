<?php

class Owner_model {
    private $db;

    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function login($username, $password) {
        return $username === 'bigboss' && $password === 'dfcjayaterus';
    }

    public function getDashboardData() {
        $today = date('Y-m-d');

        $data = [
            'tanggal_lengkap' => $this->formatTanggalIndonesia(),
            'tgl_db' => $today,
            'total_duit' => $this->getTotalOmzet($today),
            'jml_kritis' => $this->countStokKritis(),
            'total_item_terjual' => $this->getTotalItemTerjual($today),
            'outlet_aktif' => $this->countOutletBuka(),
            'request_pending' => $this->countRequestPending(),
            'chart_labels' => [],
            'chart_values' => [],
            'timeline' => [],
            'laporan_terbaru' => [],
            'menu_terlaris' => []
        ];

        $data['card_class'] = $this->getOmzetCardClass($data['total_duit']);
        $data['chart'] = $this->getChartOmzet();
        $data['timeline'] = $this->getTimeline($today);
        $data['laporan_terbaru'] = $this->getLaporanTerbaru();
        $data['menu_terlaris'] = $this->getMenuTerlaris($today);

        return $data;
    }

    public function getAllMenu() {
        $db = $this->db();
        $db->query("SELECT * FROM menu ORDER BY id DESC");
        return $db->resultSet();
    }

    public function getMenuById($id) {
        $db = $this->db();
        $db->query("SELECT * FROM menu WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->single();
    }

    public function createMenu($data) {
        $db = $this->db();
        $db->query("INSERT INTO menu (nama, harga, gambar) VALUES (:nama, :harga, :gambar)");
        $db->bind('nama', $data['nama']);
        $db->bind('harga', (int)$data['harga']);
        $db->bind('gambar', $data['gambar']);
        return $db->execute();
    }

    public function updateMenu($id, $data) {
        $db = $this->db();
        $db->query("UPDATE menu SET nama=:nama, harga=:harga, gambar=:gambar WHERE id=:id");
        $db->bind('nama', $data['nama']);
        $db->bind('harga', (int)$data['harga']);
        $db->bind('gambar', $data['gambar']);
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function deleteMenu($id) {
        $db = $this->db();
        $db->query("DELETE FROM menu WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function getAllLokasi() {
        $db = $this->db();
        $db->query("SELECT * FROM lokasi ORDER BY kategori ASC, nama ASC");
        return $db->resultSet();
    }

    public function getLokasiById($id) {
        $db = $this->db();
        $db->query("SELECT * FROM lokasi WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->single();
    }

    public function createLokasi($data) {
        $db = $this->db();
        $db->query("INSERT INTO lokasi (nama, alamat, kategori, latitude, longitude) VALUES (:nama, :alamat, :kategori, :latitude, :longitude)");
        $this->bindLokasiData($db, $data);
        return $db->execute();
    }

    public function updateLokasi($id, $data) {
        $db = $this->db();
        $db->query("UPDATE lokasi SET nama=:nama, alamat=:alamat, kategori=:kategori, latitude=:latitude, longitude=:longitude WHERE id=:id");
        $this->bindLokasiData($db, $data);
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function deleteLokasi($id) {
        $db = $this->db();
        $db->query("DELETE FROM lokasi WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function getLokasiBelumAktif() {
        $db = $this->db();
        $db->query("SELECT * FROM lokasi WHERE username_admin IS NULL OR username_admin = '' ORDER BY nama ASC");
        return $db->resultSet();
    }

    public function getOutletAktif() {
        $db = $this->db();
        $db->query("SELECT * FROM lokasi WHERE username_admin IS NOT NULL AND username_admin != '' ORDER BY nama ASC");
        return $db->resultSet();
    }

    public function usernameAdminExists($username) {
        $db = $this->db();
        $db->query("SELECT username FROM admin WHERE username=:username");
        $db->bind('username', $username);
        return (bool)$db->single();
    }

    public function activateOutlet($data) {
        $db = $this->db();
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        $db->query("INSERT INTO admin (username, password) VALUES (:username, :password)");
        $db->bind('username', $data['username']);
        $db->bind('password', $passwordHash);
        $db->execute();

        $db->query("UPDATE lokasi SET telepon=:telepon, username_admin=:username WHERE id=:id");
        $db->bind('telepon', $data['telepon']);
        $db->bind('username', $data['username']);
        $db->bind('id', (int)$data['id_lokasi']);
        $db->execute();

        $db->query("INSERT INTO pengaturan_toko (kode_outlet, status) VALUES (:username, 'buka')");
        $db->bind('username', $data['username']);
        $db->execute();

        $this->ensureMenuStockColumn($data['username']);
    }

    public function updateOutletAccount($data) {
        $db = $this->db();

        $db->query("UPDATE lokasi SET telepon=:telepon WHERE id=:id");
        $db->bind('telepon', $data['telepon']);
        $db->bind('id', (int)$data['id_lokasi']);
        $db->execute();

        if (!empty($data['password']) && !empty($data['username'])) {
            $db->query("UPDATE admin SET password=:password WHERE username=:username");
            $db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
            $db->bind('username', $data['username']);
            $db->execute();
        }
    }

    public function getAllStokGudang() {
        $db = $this->db();
        $db->query("SELECT * FROM stok_gudang ORDER BY stok ASC, nama_barang ASC");
        return $db->resultSet();
    }

    public function getRiwayatStok($limit = 50) {
        $db = $this->db();
        $db->query("SELECT * FROM riwayat_stok ORDER BY id DESC LIMIT " . (int)$limit);
        return $db->resultSet();
    }

    public function createStokGudang($data) {
        $db = $this->db();
        $db->query("INSERT INTO stok_gudang (nama_barang, stok, satuan) VALUES (:nama, :stok, :satuan)");
        $db->bind('nama', $data['nama_barang']);
        $db->bind('stok', (int)$data['stok']);
        $db->bind('satuan', $data['satuan']);
        $db->execute();
        $this->catatRiwayatStok($data['nama_barang'], 0, (int)$data['stok'], (int)$data['stok'], 'Owner (Baru)');
    }

    public function updateStokGudang($data) {
        $barang = $this->getStokGudangById($data['id']);
        if (!$barang) return;

        $stokLama = (int)$barang['stok'];
        $stokBaru = (int)$data['stok'];
        $selisih = $stokBaru - $stokLama;

        $db = $this->db();
        $db->query("UPDATE stok_gudang SET nama_barang=:nama, stok=:stok, satuan=:satuan WHERE id=:id");
        $db->bind('nama', $data['nama_barang']);
        $db->bind('stok', $stokBaru);
        $db->bind('satuan', $data['satuan']);
        $db->bind('id', (int)$data['id']);
        $db->execute();

        if ($selisih !== 0) {
            $this->catatRiwayatStok($data['nama_barang'], $stokLama, $stokBaru, $selisih, 'Owner (Edit)');
        }
    }

    public function deleteStokGudang($id) {
        $barang = $this->getStokGudangById($id);
        if (!$barang) return;

        $db = $this->db();
        $db->query("DELETE FROM stok_gudang WHERE id=:id");
        $db->bind('id', (int)$id);
        $db->execute();
        $this->catatRiwayatStok($barang['nama_barang'], 0, 0, 0, 'Owner (Hapus)');
    }

    public function getAllRequestStok() {
        $db = $this->db();
        $db->query("SELECT * FROM request_stok ORDER BY id DESC");
        return $db->resultSet();
    }

    public function updateRequestStok($id, $status, $alasan = '-') {
        $db = $this->db();
        $db->query("UPDATE request_stok SET status=:status, alasan_tolak=:alasan WHERE id=:id");
        $db->bind('status', $status);
        $db->bind('alasan', $alasan);
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function getAdminGudang() {
        $db = $this->db();
        $db->query("SELECT * FROM admin_gudang WHERE id=1 LIMIT 1");
        return $db->single();
    }

    public function updateAdminGudang($username, $password) {
        $db = $this->db();
        $db->query("UPDATE admin_gudang SET username=:username, password=:password WHERE id=1");
        $db->bind('username', $username);
        $db->bind('password', $password);
        return $db->execute();
    }

    public function getProfilPerusahaan() {
        $db = $this->db();
        $db->query("SELECT * FROM profil_perusahaan WHERE id=1");
        return $db->single();
    }

    public function updateProfilPerusahaan($data) {
        $db = $this->db();
        $db->query("UPDATE profil_perusahaan SET sejarah=:sejarah, visi=:visi, misi=:misi WHERE id=1");
        $db->bind('sejarah', $data['sejarah']);
        $db->bind('visi', $data['visi']);
        $db->bind('misi', $data['misi']);
        return $db->execute();
    }

    public function getPengajuanMitra() {
        $db = $this->db();
        $db->query("SELECT * FROM pengajuan_mitra ORDER BY id DESC");
        return $db->resultSet();
    }

    public function deletePengajuanMitra($id) {
        $db = $this->db();
        $db->query("DELETE FROM pengajuan_mitra WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function getAkunMitra() {
        $db = $this->db();
        $db->query("SELECT * FROM akun_mitra ORDER BY id DESC");
        return $db->resultSet();
    }

    public function usernameMitraExists($username) {
        $db = $this->db();
        $db->query("SELECT username FROM akun_mitra WHERE username=:username");
        $db->bind('username', $username);
        return (bool)$db->single();
    }

    public function createAkunMitra($data) {
        $db = $this->db();
        $db->query("INSERT INTO akun_mitra (nama_mitra, username, password) VALUES (:nama, :username, :password)");
        $db->bind('nama', $data['nama']);
        $db->bind('username', $data['username']);
        $db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        return $db->execute();
    }

    public function deleteAkunMitra($id) {
        $db = $this->db();
        $db->query("DELETE FROM akun_mitra WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->execute();
    }

    public function getLaporanKeuangan($bulan, $tahun) {
        $db = $this->db();
        $db->query("SELECT * FROM laporan_keuangan WHERE MONTH(tanggal)=:bulan AND YEAR(tanggal)=:tahun ORDER BY tanggal DESC");
        $db->bind('bulan', $bulan);
        $db->bind('tahun', $tahun);
        return $db->resultSet();
    }

    public function getRiwayatPenjualanWeb($tglMulai, $tglAkhir, $outlet = 'semua') {
        $db = $this->db();
        if ($outlet !== 'semua') {
            $db->query("SELECT * FROM transaksi_pesanan WHERE DATE(waktu_pesan) BETWEEN :mulai AND :akhir AND kode_outlet=:outlet ORDER BY waktu_pesan DESC");
            $db->bind('outlet', $outlet);
        } else {
            $db->query("SELECT * FROM transaksi_pesanan WHERE DATE(waktu_pesan) BETWEEN :mulai AND :akhir ORDER BY waktu_pesan DESC");
        }
        $db->bind('mulai', $tglMulai);
        $db->bind('akhir', $tglAkhir);
        return $db->resultSet();
    }

    public function getLogAktivitas($limit = 100) {
        $db = $this->db();
        $db->query("SELECT * FROM log_aktivitas ORDER BY waktu DESC LIMIT " . (int)$limit);
        return $db->resultSet();
    }

    private function formatTanggalIndonesia() {
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return date('d') . ' ' . $bulan[date('m')] . ' ' . date('Y');
    }

    private function bindLokasiData($db, $data) {
        $db->bind('nama', $data['nama']);
        $db->bind('alamat', $data['alamat']);
        $db->bind('kategori', $data['kategori']);
        $db->bind('latitude', $data['latitude']);
        $db->bind('longitude', $data['longitude']);
    }

    private function getStokGudangById($id) {
        $db = $this->db();
        $db->query("SELECT * FROM stok_gudang WHERE id=:id");
        $db->bind('id', (int)$id);
        return $db->single();
    }

    private function catatRiwayatStok($namaBarang, $stokLama, $stokBaru, $selisih, $admin) {
        $db = $this->db();
        $db->query("INSERT INTO riwayat_stok (nama_barang, stok_lama, stok_baru, selisih, admin) VALUES (:nama, :lama, :baru, :selisih, :admin)");
        $db->bind('nama', $namaBarang);
        $db->bind('lama', (int)$stokLama);
        $db->bind('baru', (int)$stokBaru);
        $db->bind('selisih', (int)$selisih);
        $db->bind('admin', $admin);
        $db->execute();
    }

    private function ensureMenuStockColumn($username) {
        $column = 'stok_' . $username;
        if (!preg_match('/^stok_[a-zA-Z0-9]+$/', $column)) {
            return;
        }

        $db = $this->db();
        $db->query("SHOW COLUMNS FROM menu LIKE :column_name");
        $db->bind('column_name', $column);
        if ($db->single()) {
            return;
        }

        $db->query("ALTER TABLE menu ADD COLUMN `$column` INT DEFAULT 0");
        $db->execute();
    }

    private function getTotalOmzet($tanggal) {
        $db = $this->db();
        $db->query("SELECT SUM(omzet_bersih) AS total FROM laporan_keuangan WHERE tanggal=:tanggal");
        $db->bind('tanggal', $tanggal);
        $result = $db->single();
        return (int)($result['total'] ?? 0);
    }

    private function countStokKritis() {
        $db = $this->db();
        $db->query("SELECT COUNT(*) AS total FROM stok_gudang WHERE stok < 10");
        $result = $db->single();
        return (int)($result['total'] ?? 0);
    }

    private function getTotalItemTerjual($tanggal) {
        $db = $this->db();
        $db->query("SELECT SUM(qty) AS total_item FROM penjualan_harian WHERE tanggal=:tanggal");
        $db->bind('tanggal', $tanggal);
        $result = $db->single();
        return (int)($result['total_item'] ?? 0);
    }

    private function countOutletBuka() {
        $db = $this->db();
        $db->query("SELECT COUNT(*) AS total FROM pengaturan_toko WHERE status='buka'");
        $result = $db->single();
        return (int)($result['total'] ?? 0);
    }

    private function countRequestPending() {
        $db = $this->db();
        $db->query("SELECT COUNT(*) AS total FROM request_stok WHERE status='Pending'");
        $result = $db->single();
        return (int)($result['total'] ?? 0);
    }

    private function getOmzetCardClass($total) {
        if ($total > 0 && $total < 200000) return 'omzet-biru';
        if ($total >= 200000 && $total < 500000) return 'omzet-hijau';
        if ($total >= 500000 && $total < 1000000) return 'omzet-oranye';
        if ($total >= 1000000) return 'omzet-merah';
        return 'omzet-putih';
    }

    private function getChartOmzet() {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime("-$i days"));

            $db = $this->db();
            $db->query("SELECT SUM(omzet_bersih) AS total_harian FROM laporan_keuangan WHERE tanggal=:tanggal");
            $db->bind('tanggal', $tanggal);
            $result = $db->single();
            $values[] = (int)($result['total_harian'] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    private function getTimeline($today) {
        $timeline = [];
        $db = $this->db();

        $db->query("SELECT waktu, kode_outlet, nama_menu, subtotal FROM penjualan_harian WHERE tanggal=:tanggal ORDER BY waktu DESC LIMIT 5");
        $db->bind('tanggal', $today);
        foreach ($db->resultSet() as $row) {
            $timeline[] = [
                'waktu' => $row['waktu'],
                'outlet' => ucfirst($row['kode_outlet']),
                'teks' => "Menjual <b>" . htmlspecialchars($row['nama_menu']) . "</b> senilai <b style='color:#2e7d32;'>Rp " . number_format($row['subtotal']) . "</b>",
                'icon' => 'ph-fill ph-shopping-cart',
                'color' => '#2e7d32',
                'bg' => '#e8f5e9'
            ];
        }

        $db->query("SELECT waktu_request, nama_outlet, nama_barang FROM request_stok ORDER BY waktu_request DESC LIMIT 5");
        foreach ($db->resultSet() as $row) {
            if (date('Y-m-d', strtotime($row['waktu_request'])) !== $today) continue;

            $timeline[] = [
                'waktu' => date('H:i:s', strtotime($row['waktu_request'])),
                'outlet' => $row['nama_outlet'],
                'teks' => "Meminta restock <b>" . htmlspecialchars($row['nama_barang']) . "</b> ke Gudang.",
                'icon' => 'ph-fill ph-truck',
                'color' => '#1565c0',
                'bg' => '#e3f2fd'
            ];
        }

        $db->query("SELECT waktu_input, kode_outlet, omzet_bersih FROM laporan_keuangan WHERE tanggal=:tanggal ORDER BY waktu_input DESC LIMIT 5");
        $db->bind('tanggal', $today);
        foreach ($db->resultSet() as $row) {
            $timeline[] = [
                'waktu' => $row['waktu_input'],
                'outlet' => ucfirst($row['kode_outlet']),
                'teks' => "Menyetor laporan dengan Omzet Bersih: <b style='color:#e65100;'>Rp " . number_format($row['omzet_bersih']) . "</b>",
                'icon' => 'ph-fill ph-file-text',
                'color' => '#e65100',
                'bg' => '#fff3e0'
            ];
        }

        usort($timeline, function($a, $b) {
            return strtotime($b['waktu']) - strtotime($a['waktu']);
        });

        return array_slice($timeline, 0, 6);
    }

    private function getLaporanTerbaru() {
        $db = $this->db();
        $db->query("SELECT * FROM laporan_keuangan ORDER BY id DESC LIMIT 5");
        return $db->resultSet();
    }

    private function getMenuTerlaris($tanggal) {
        $db = $this->db();
        $db->query("SELECT nama_menu, SUM(qty) AS total_terjual FROM penjualan_harian WHERE tanggal=:tanggal GROUP BY nama_menu ORDER BY total_terjual DESC LIMIT 5");
        $db->bind('tanggal', $tanggal);
        return $db->resultSet();
    }

    private function db() {
        if (!$this->db) {
            $this->db = new Database;
        }

        return $this->db;
    }
}
