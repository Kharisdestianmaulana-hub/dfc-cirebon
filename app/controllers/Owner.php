<?php

class Owner extends Controller {
    private $legacyRoutes = [
        'laporan-full' => ['title' => 'Laporan Keuangan', 'legacy' => 'laporan-full.php'],
        'stok-gudang' => ['title' => 'Stok Gudang Pusat', 'legacy' => 'stok-gudang.php'],
        'request-stok' => ['title' => 'Permintaan Stok', 'legacy' => 'request-stok.php'],
        'atur-gudang' => ['title' => 'Atur Password Gudang', 'legacy' => 'atur-gudang.php'],
        'kelola-menu' => ['title' => 'Kelola Menu Makanan', 'legacy' => 'kelola-menu.php'],
        'menu' => ['title' => 'Kelola Menu Makanan', 'legacy' => 'kelola-menu.php'],
        'kelola-lokasi' => ['title' => 'Kelola Lokasi Outlet', 'legacy' => 'kelola-lokasi.php'],
        'lokasi' => ['title' => 'Kelola Lokasi Outlet', 'legacy' => 'kelola-lokasi.php'],
        'log-aktivitas' => ['title' => 'Log Aktivitas Karyawan', 'legacy' => 'log-aktivitas.php'],
        'riwayat-penjualan' => ['title' => 'Riwayat Penjualan', 'legacy' => 'riwayat-penjualan.php'],
        'atur-profil' => ['title' => 'Atur Profil Perusahaan', 'legacy' => 'atur-profil.php'],
        'profil' => ['title' => 'Atur Profil Perusahaan', 'legacy' => 'atur-profil.php'],
        'kelola-kemitraan' => ['title' => 'Data Kemitraan', 'legacy' => 'kelola-kemitraan.php'],
        'kemitraan' => ['title' => 'Data Kemitraan', 'legacy' => 'kelola-kemitraan.php'],
        'akun-mitra' => ['title' => 'Akun Kemitraan', 'legacy' => 'akun-mitra.php'],
    ];

    public function index($page = '', $action = '', $id = '') {
        if ($page !== '') {
            if ($page === 'kelola-menu') {
                header('Location: ' . BASEURL . '/owner/menu');
                exit;
            }

            if ($page === 'form-menu') {
                header('Location: ' . BASEURL . '/owner/menu/form');
                exit;
            }

            if ($page === 'kelola-lokasi') {
                header('Location: ' . BASEURL . '/owner/lokasi');
                exit;
            }

            if ($page === 'form-lokasi') {
                header('Location: ' . BASEURL . '/owner/lokasi/form');
                exit;
            }

            if ($page === 'outlet-karyawan') {
                $this->outletKaryawan($action);
                return;
            }

            $routeMap = [
                'stok-gudang' => 'stokGudang',
                'request-stok' => 'requestStok',
                'atur-gudang' => 'aturGudang',
                'laporan-full' => 'laporanFull',
                'riwayat-penjualan' => 'riwayatPenjualan',
                'log-aktivitas' => 'logAktivitas',
                'kelola-kemitraan' => 'kemitraan',
                'akun-mitra' => 'akunMitra',
                'atur-profil' => 'profil',
            ];

            if (isset($routeMap[$page])) {
                $method = $routeMap[$page];
                $this->$method($action, $id);
                return;
            }

            $this->legacyPlaceholder($page);
            return;
        }

        $this->dashboard();
    }

    public function dashboard() {
        $this->requireLogin();

        $model = $this->model('Owner_model');
        $data = $model->getDashboardData();
        $data['judul'] = 'Dashboard Owner - DFC Cirebon';
        $data['active'] = 'dashboard';

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/dashboard', $data);
        $this->view('owner/templates/footer', $data);
    }

    public function login() {
        if (isset($_SESSION['boss_login'])) {
            header('Location: ' . BASEURL . '/owner/dashboard');
            exit;
        }

        $data = [
            'judul' => 'Login Owner DFC Cirebon',
            'error' => ''
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $username = $_POST['user'] ?? '';
            $password = $_POST['pass'] ?? '';

            if ($this->model('Owner_model')->login($username, $password)) {
                $_SESSION['boss_login'] = true;
                $_SESSION['owner_username'] = $username;
                header('Location: ' . BASEURL . '/owner/dashboard');
                exit;
            }

            $data['error'] = 'Username atau password owner salah.';
        }

        $this->view('owner/login', $data);
    }

    public function logout() {
        unset($_SESSION['boss_login'], $_SESSION['owner_username']);
        header('Location: ' . BASEURL . '/owner/login');
        exit;
    }

    public function laporan() {
        $this->legacyPlaceholder('laporan-full');
    }

    public function menu($action = '', $id = '') {
        $this->requireLogin();

        if ($action === 'form') {
            $this->menuForm($id);
            return;
        }

        if ($action === 'simpan') {
            $this->simpanMenu();
            return;
        }

        if ($action === 'hapus') {
            $this->hapusMenu($id);
            return;
        }

        $model = $this->model('Owner_model');
        $data = [
            'judul' => 'Kelola Menu - DFC Owner',
            'active' => 'menu',
            'menu' => $model->getAllMenu(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ];
        unset($_SESSION['owner_flash']);

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/menu/index', $data);
        $this->view('owner/templates/footer', $data);
    }

    public function lokasi($action = '', $id = '') {
        $this->requireLogin();

        if ($action === 'form') {
            $this->lokasiForm($id);
            return;
        }

        if ($action === 'simpan') {
            $this->simpanLokasi();
            return;
        }

        if ($action === 'hapus') {
            $this->hapusLokasi($id);
            return;
        }

        $model = $this->model('Owner_model');
        $data = [
            'judul' => 'Kelola Lokasi - DFC Owner',
            'active' => 'lokasi',
            'lokasi' => $model->getAllLokasi(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ];
        unset($_SESSION['owner_flash']);

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/lokasi/index', $data);
        $this->view('owner/templates/footer', $data);
    }

    public function outlet_karyawan($action = '') {
        $this->outletKaryawan($action);
    }

    public function outletKaryawan($action = '') {
        $this->requireLogin();

        if ($action === 'aktifkan') {
            $this->aktifkanOutlet();
            return;
        }

        if ($action === 'update') {
            $this->updateOutletKaryawan();
            return;
        }

        $model = $this->model('Owner_model');
        $data = [
            'judul' => 'Aktivasi Outlet & Karyawan - DFC Owner',
            'active' => 'lokasi',
            'lokasi_belum_aktif' => $model->getLokasiBelumAktif(),
            'outlet_aktif' => $model->getOutletAktif(),
            'flash' => $_SESSION['owner_flash'] ?? '',
            'error' => $_SESSION['owner_error'] ?? ''
        ];
        unset($_SESSION['owner_flash'], $_SESSION['owner_error']);

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/lokasi/outlet_karyawan', $data);
        $this->view('owner/templates/footer', $data);
    }

    public function profil($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $model->updateProfilPerusahaan([
                'sejarah' => $_POST['sejarah'] ?? '',
                'visi' => $_POST['visi'] ?? '',
                'misi' => $_POST['misi'] ?? ''
            ]);
            $_SESSION['owner_flash'] = 'Profil perusahaan berhasil diperbarui.';
            header('Location: ' . BASEURL . '/owner/profil');
            exit;
        }

        $data = $this->ownerPageData('Atur Profil Perusahaan', 'profil', [
            'profil' => $model->getProfilPerusahaan(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ]);
        unset($_SESSION['owner_flash']);
        $this->renderOwnerPage('owner/sisa/profil', $data);
    }

    public function kemitraan($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $action === 'hapus' && $id !== '') {
            $model->deletePengajuanMitra($id);
            $_SESSION['owner_flash'] = 'Data pengajuan mitra berhasil dihapus.';
            header('Location: ' . BASEURL . '/owner/kemitraan');
            exit;
        }

        $data = $this->ownerPageData('Data Kemitraan', 'kemitraan', [
            'pengajuan' => $model->getPengajuanMitra(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ]);
        unset($_SESSION['owner_flash']);
        $this->renderOwnerPage('owner/sisa/kemitraan', $data);
    }

    public function stokGudang($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if ($action === 'tambah') {
                $model->createStokGudang([
                    'nama_barang' => trim($_POST['nama_barang'] ?? ''),
                    'stok' => (int)($_POST['stok'] ?? $_POST['stok_awal'] ?? 0),
                    'satuan' => trim($_POST['satuan'] ?? '')
                ]);
                $_SESSION['owner_flash'] = 'Barang gudang berhasil ditambahkan.';
            } elseif ($action === 'edit') {
                $model->updateStokGudang([
                    'id' => $_POST['id_barang'] ?? '',
                    'nama_barang' => trim($_POST['nama_barang'] ?? ''),
                    'stok' => (int)($_POST['stok'] ?? 0),
                    'satuan' => trim($_POST['satuan'] ?? '')
                ]);
                $_SESSION['owner_flash'] = 'Stok gudang berhasil diupdate.';
            } elseif ($action === 'hapus' && $id !== '') {
                $model->deleteStokGudang($id);
                $_SESSION['owner_flash'] = 'Barang gudang berhasil dihapus.';
            }
            header('Location: ' . BASEURL . '/owner/stok-gudang');
            exit;
        }

        $data = $this->ownerPageData('Stok Gudang Pusat', 'stok-gudang', [
            'stok' => $model->getAllStokGudang(),
            'riwayat' => $model->getRiwayatStok(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ]);
        unset($_SESSION['owner_flash']);
        $this->renderOwnerPage('owner/sisa/stok_gudang', $data);
    }

    public function requestStok($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if ($action === 'approve') {
                $model->updateRequestStok($_POST['id_request'] ?? $id, 'Disetujui', '-');
                $_SESSION['owner_flash'] = 'Permintaan stok disetujui.';
            } elseif ($action === 'tolak') {
                $model->updateRequestStok($_POST['id_request'] ?? $id, 'Ditolak', trim($_POST['alasan'] ?? '-'));
                $_SESSION['owner_flash'] = 'Permintaan stok ditolak.';
            }
            header('Location: ' . BASEURL . '/owner/request-stok');
            exit;
        }

        $data = $this->ownerPageData('Permintaan Stok Outlet', 'request-stok', [
            'requests' => $model->getAllRequestStok(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ]);
        unset($_SESSION['owner_flash']);
        $this->renderOwnerPage('owner/sisa/request_stok', $data);
    }

    public function aturGudang($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $model->updateAdminGudang(trim($_POST['username'] ?? ''), trim($_POST['password'] ?? ''));
            $_SESSION['owner_flash'] = 'Akun admin gudang berhasil diupdate.';
            header('Location: ' . BASEURL . '/owner/atur-gudang');
            exit;
        }

        $data = $this->ownerPageData('Atur Password Gudang', 'atur-gudang', [
            'gudang' => $model->getAdminGudang(),
            'flash' => $_SESSION['owner_flash'] ?? ''
        ]);
        unset($_SESSION['owner_flash']);
        $this->renderOwnerPage('owner/sisa/atur_gudang', $data);
    }

    public function laporanFull($action = '', $id = '') {
        $this->requireLogin();
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $model = $this->model('Owner_model');
        $data = $this->ownerPageData('Laporan Keuangan', 'laporan-full', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'laporan' => $model->getLaporanKeuangan($bulan, $tahun),
            'list_bulan' => $this->listBulan()
        ]);
        $this->renderOwnerPage('owner/sisa/laporan', $data);
    }

    public function riwayatPenjualan($action = '', $id = '') {
        $this->requireLogin();
        $mulai = $_GET['tgl_mulai'] ?? date('Y-m-d');
        $akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
        $outlet = $_GET['outlet'] ?? 'semua';
        $model = $this->model('Owner_model');
        $data = $this->ownerPageData('Riwayat Penjualan Web', 'riwayat-penjualan', [
            'tgl_mulai' => $mulai,
            'tgl_akhir' => $akhir,
            'filter_outlet' => $outlet,
            'orders' => $model->getRiwayatPenjualanWeb($mulai, $akhir, $outlet),
            'outlets' => $model->getOutletAktif()
        ]);
        $this->renderOwnerPage('owner/sisa/riwayat_penjualan', $data);
    }

    public function logAktivitas($action = '', $id = '') {
        $this->requireLogin();
        $data = $this->ownerPageData('Log Aktivitas Karyawan', 'log-aktivitas', [
            'logs' => $this->model('Owner_model')->getLogAktivitas()
        ]);
        $this->renderOwnerPage('owner/sisa/log_aktivitas', $data);
    }

    public function akunMitra($action = '', $id = '') {
        $this->requireLogin();
        $model = $this->model('Owner_model');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if ($action === 'tambah') {
                $username = trim($_POST['username'] ?? '');
                if ($model->usernameMitraExists($username)) {
                    $_SESSION['owner_error'] = 'Username mitra sudah dipakai.';
                } else {
                    $model->createAkunMitra([
                        'nama' => trim($_POST['nama'] ?? ''),
                        'username' => $username,
                        'password' => $_POST['password'] ?? ''
                    ]);
                    $_SESSION['owner_flash'] = 'Akun mitra berhasil dibuat.';
                }
            } elseif ($action === 'hapus' && $id !== '') {
                $model->deleteAkunMitra($id);
                $_SESSION['owner_flash'] = 'Akun mitra berhasil dihapus.';
            }
            header('Location: ' . BASEURL . '/owner/akun-mitra');
            exit;
        }

        $data = $this->ownerPageData('Akun Kemitraan', 'akun-mitra', [
            'akun' => $model->getAkunMitra(),
            'flash' => $_SESSION['owner_flash'] ?? '',
            'error' => $_SESSION['owner_error'] ?? ''
        ]);
        unset($_SESSION['owner_flash'], $_SESSION['owner_error']);
        $this->renderOwnerPage('owner/sisa/akun_mitra', $data);
    }

    public function hapusKemitraan($id = '') {
        $this->requireLogin();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $id !== '') {
            $this->model('Owner_model')->deletePengajuanMitra($id);
            $_SESSION['owner_flash'] = 'Data pengajuan mitra berhasil dihapus.';
        }
        header('Location: ' . BASEURL . '/owner/kemitraan');
        exit;
    }

    private function legacyPlaceholder($page) {
        $this->requireLogin();

        $route = $this->legacyRoutes[$page] ?? null;
        if (!$route) {
            header('Location: ' . BASEURL . '/owner/dashboard');
            exit;
        }

        $data = [
            'judul' => $route['title'] . ' - DFC Owner',
            'active' => $page,
            'page_title' => $route['title'],
            'legacy_url' => BASEURL . '/owner/' . $route['legacy']
        ];

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/placeholder', $data);
        $this->view('owner/templates/footer', $data);
    }

    private function requireLogin() {
        if (!isset($_SESSION['boss_login'])) {
            header('Location: ' . BASEURL . '/owner/login');
            exit;
        }
    }

    private function ownerPageData($title, $active, $extra = []) {
        return array_merge([
            'judul' => $title . ' - DFC Owner',
            'active' => $active,
            'page_title' => $title
        ], $extra);
    }

    private function renderOwnerPage($view, $data) {
        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view($view, $data);
        $this->view('owner/templates/footer', $data);
    }

    private function listBulan() {
        return [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
    }

    private function menuForm($id = '') {
        $model = $this->model('Owner_model');
        $menu = [
            'id' => '',
            'nama' => '',
            'harga' => '',
            'gambar' => ''
        ];
        $mode = 'tambah';

        if ($id !== '') {
            $menu = $model->getMenuById($id);
            if (!$menu) {
                $_SESSION['owner_flash'] = 'Menu tidak ditemukan.';
                header('Location: ' . BASEURL . '/owner/menu');
                exit;
            }
            $mode = 'edit';
        }

        $data = [
            'judul' => ucfirst($mode) . ' Menu - DFC Owner',
            'active' => 'menu',
            'mode' => $mode,
            'menu_item' => $menu,
            'error' => $_SESSION['owner_error'] ?? ''
        ];
        unset($_SESSION['owner_error']);

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/menu/form', $data);
        $this->view('owner/templates/footer', $data);
    }

    private function simpanMenu() {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . BASEURL . '/owner/menu');
            exit;
        }

        $id = $_POST['id'] ?? '';
        $nama = trim($_POST['nama'] ?? '');
        $harga = (int)($_POST['harga'] ?? 0);
        $gambarLama = $_POST['gambar_lama'] ?? '';

        if ($nama === '' || $harga <= 0) {
            $_SESSION['owner_error'] = 'Nama menu dan harga wajib diisi dengan benar.';
            $target = $id ? '/owner/menu/form/' . urlencode($id) : '/owner/menu/form';
            header('Location: ' . BASEURL . $target);
            exit;
        }

        $gambarFinal = $gambarLama;
        $upload = $this->uploadMenuImage();
        if ($upload['error']) {
            $_SESSION['owner_error'] = $upload['error'];
            $target = $id ? '/owner/menu/form/' . urlencode($id) : '/owner/menu/form';
            header('Location: ' . BASEURL . $target);
            exit;
        }

        if ($upload['path']) {
            $gambarFinal = $upload['path'];
            $this->deleteMenuImage($gambarLama);
        }

        $model = $this->model('Owner_model');
        $data = [
            'nama' => $nama,
            'harga' => $harga,
            'gambar' => $gambarFinal
        ];

        if ($id) {
            $model->updateMenu($id, $data);
            $_SESSION['owner_flash'] = 'Menu berhasil diupdate.';
        } else {
            $model->createMenu($data);
            $_SESSION['owner_flash'] = 'Menu baru berhasil ditambahkan.';
        }

        header('Location: ' . BASEURL . '/owner/menu');
        exit;
    }

    private function hapusMenu($id = '') {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || $id === '') {
            header('Location: ' . BASEURL . '/owner/menu');
            exit;
        }

        $model = $this->model('Owner_model');
        $menu = $model->getMenuById($id);

        if ($menu) {
            $this->deleteMenuImage($menu['gambar']);
            $model->deleteMenu($id);
            $_SESSION['owner_flash'] = 'Menu berhasil dihapus.';
        } else {
            $_SESSION['owner_flash'] = 'Menu tidak ditemukan.';
        }

        header('Location: ' . BASEURL . '/owner/menu');
        exit;
    }

    private function lokasiForm($id = '') {
        $model = $this->model('Owner_model');
        $lokasi = [
            'id' => '',
            'nama' => '',
            'alamat' => '',
            'kategori' => '',
            'latitude' => '',
            'longitude' => ''
        ];
        $mode = 'tambah';

        if ($id !== '') {
            $lokasi = $model->getLokasiById($id);
            if (!$lokasi) {
                $_SESSION['owner_flash'] = 'Lokasi tidak ditemukan.';
                header('Location: ' . BASEURL . '/owner/lokasi');
                exit;
            }
            $mode = 'edit';
        }

        $data = [
            'judul' => ucfirst($mode) . ' Lokasi - DFC Owner',
            'active' => 'lokasi',
            'mode' => $mode,
            'lokasi_item' => $lokasi,
            'error' => $_SESSION['owner_error'] ?? ''
        ];
        unset($_SESSION['owner_error']);

        $this->view('owner/templates/header', $data);
        $this->view('owner/templates/sidebar', $data);
        $this->view('owner/lokasi/form', $data);
        $this->view('owner/templates/footer', $data);
    }

    private function simpanLokasi() {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . BASEURL . '/owner/lokasi');
            exit;
        }

        $id = $_POST['id'] ?? '';
        $payload = [
            'nama' => trim($_POST['nama'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? ''),
            'kategori' => $_POST['kategori'] ?? '',
            'latitude' => trim($_POST['latitude'] ?? ''),
            'longitude' => trim($_POST['longitude'] ?? '')
        ];

        if ($payload['nama'] === '' || $payload['alamat'] === '' || $payload['kategori'] === '' || $payload['latitude'] === '' || $payload['longitude'] === '') {
            $_SESSION['owner_error'] = 'Semua data lokasi wajib diisi.';
            $target = $id ? '/owner/lokasi/form/' . urlencode($id) : '/owner/lokasi/form';
            header('Location: ' . BASEURL . $target);
            exit;
        }

        $model = $this->model('Owner_model');
        if ($id) {
            $model->updateLokasi($id, $payload);
            $_SESSION['owner_flash'] = 'Lokasi berhasil diupdate.';
        } else {
            $model->createLokasi($payload);
            $_SESSION['owner_flash'] = 'Lokasi baru berhasil ditambahkan.';
        }

        header('Location: ' . BASEURL . '/owner/lokasi');
        exit;
    }

    private function hapusLokasi($id = '') {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || $id === '') {
            header('Location: ' . BASEURL . '/owner/lokasi');
            exit;
        }

        $this->model('Owner_model')->deleteLokasi($id);
        $_SESSION['owner_flash'] = 'Lokasi berhasil dihapus.';

        header('Location: ' . BASEURL . '/owner/lokasi');
        exit;
    }

    private function aktifkanOutlet() {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . BASEURL . '/owner/outlet-karyawan');
            exit;
        }

        $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['username'] ?? ''));
        $payload = [
            'id_lokasi' => $_POST['id_lokasi'] ?? '',
            'username' => $username,
            'password' => $_POST['password'] ?? '',
            'telepon' => trim($_POST['telepon'] ?? '')
        ];

        if (!$payload['id_lokasi'] || $payload['username'] === '' || $payload['password'] === '' || $payload['telepon'] === '') {
            $_SESSION['owner_error'] = 'Data aktivasi outlet wajib diisi lengkap.';
            header('Location: ' . BASEURL . '/owner/outlet-karyawan');
            exit;
        }

        $model = $this->model('Owner_model');
        if ($model->usernameAdminExists($payload['username'])) {
            $_SESSION['owner_error'] = 'Username sudah terpakai.';
            header('Location: ' . BASEURL . '/owner/outlet-karyawan');
            exit;
        }

        $model->activateOutlet($payload);
        $_SESSION['owner_flash'] = 'Outlet berhasil diaktifkan.';

        header('Location: ' . BASEURL . '/owner/outlet-karyawan');
        exit;
    }

    private function updateOutletKaryawan() {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . BASEURL . '/owner/outlet-karyawan');
            exit;
        }

        $payload = [
            'id_lokasi' => $_POST['id_lokasi_edit'] ?? '',
            'telepon' => trim($_POST['telepon_edit'] ?? ''),
            'username' => $_POST['username_lama'] ?? '',
            'password' => $_POST['password_edit'] ?? ''
        ];

        if (!$payload['id_lokasi'] || $payload['telepon'] === '') {
            $_SESSION['owner_error'] = 'Outlet dan nomor telepon wajib diisi.';
            header('Location: ' . BASEURL . '/owner/outlet-karyawan');
            exit;
        }

        $this->model('Owner_model')->updateOutletAccount($payload);
        $_SESSION['owner_flash'] = !empty($payload['password'])
            ? 'Data outlet dan password berhasil diupdate.'
            : 'Nomor telepon outlet berhasil diupdate.';

        header('Location: ' . BASEURL . '/owner/outlet-karyawan');
        exit;
    }

    private function uploadMenuImage() {
        if (!isset($_FILES['foto']) || ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['path' => '', 'error' => ''];
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            return ['path' => '', 'error' => 'Upload gambar gagal. Coba pilih file lain.'];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $originalName = $_FILES['foto']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return ['path' => '', 'error' => 'Format gambar harus JPG, PNG, JPEG, atau WEBP.'];
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($originalName));
        $fileName = time() . '_' . $safeName;
        $relativePath = 'img/' . $fileName;
        $targetPath = dirname(__DIR__, 2) . '/assets/img/owner/' . $fileName;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
            return ['path' => '', 'error' => 'Gambar gagal disimpan ke folder assets/img/owner.'];
        }

        return ['path' => $relativePath, 'error' => ''];
    }

    private function deleteMenuImage($gambar) {
        $fileName = basename($gambar ?? '');
        if ($fileName === '') return;

        $path = dirname(__DIR__, 2) . '/assets/img/owner/' . $fileName;
        if (is_file($path)) {
            unlink($path);
        }
    }
}
