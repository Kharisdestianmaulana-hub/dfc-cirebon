<?php
require_once __DIR__ . '/../models/MitraModel.php';

/**
 * Class AuthController
 * Mengurus proses login dan logout mitra (satpam gudang).
 */
class AuthController extends Controller
{
    private MitraModel $mitraModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
    }

    /**
     * Tampilkan form login, dan proses submit-nya.
     */
    public function login(): void
    {
        // Jika sudah login, langsung lempar ke dashboard stok
        if (isset($_SESSION['mitra_login'])) {
            $this->redirect('stok');
        }

        $pesanError = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $akun = $this->mitraModel->findByUsername($username);

            if ($akun) {
                if (password_verify($password, $akun['password'])) {
                    // Regenerasi session id demi keamanan (mencegah session fixation)
                    session_regenerate_id(true);

                    $_SESSION['mitra_login'] = true;
                    $_SESSION['nama_mitra']  = $akun['nama_mitra'];

                    $this->redirect('stok');
                } else {
                    $pesanError = "Password yang Anda masukkan salah!";
                }
            } else {
                $pesanError = "Username tidak terdaftar!";
            }
        }

        $this->view('auth/login', ['pesanError' => $pesanError]);
    }

    /**
     * Hapus session dan kembalikan ke halaman login.
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('login');
    }
}
