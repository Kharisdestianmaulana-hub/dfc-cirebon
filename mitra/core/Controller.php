<?php
/**
 * Class Controller (Base)
 * Semua controller lain diturunkan dari class ini.
 */
class Controller
{
    /**
     * Merender file view dengan data yang di-pass sebagai variabel.
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("View tidak ditemukan: {$view}");
        }

        require $viewPath;
    }

    /**
     * Redirect ke route lain (menggunakan front controller index.php?page=...)
     */
    protected function redirect(string $page): void
    {
        header("Location: " . BASEURL . "/index.php?page=" . $page);
        exit;
    }

    /**
     * Mengirim response dalam format JSON (untuk endpoint AJAX / API).
     */
    protected function json($data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Memastikan mitra sudah login, jika belum lempar ke halaman login.
     */
    protected function requireLogin(): void
    {
        if (!isset($_SESSION['mitra_login'])) {
            $this->redirect('login');
        }
    }
}
