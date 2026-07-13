<?php
abstract class BaseController
{
    protected function session(): void { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); date_default_timezone_set('Asia/Jakarta'); }
    protected function requireLogin(): void { $this->session(); if (empty($_SESSION['isLoggedIn'])) { header('Location: admin-login.php'); exit; } }
    protected function view(string $name, array $data = []): void { extract($data, EXTR_SKIP); require __DIR__ . '/../views/' . $name . '.php'; }
    protected function redirect(string $path): void { header('Location: ' . $path); exit; }
}
