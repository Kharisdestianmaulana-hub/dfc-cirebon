<?php
require_once __DIR__ . '/BaseModel.php';

class Auth extends BaseModel
{
    public function login(string $username, string $password): ?array
    {
        $username = trim($username);
        $admin = $this->fetchOne(
            'SELECT username, password FROM admin WHERE username = :username',
            ['username' => $username]
        );
        if (!$admin || !password_verify($password, $admin['password'])) {
            return null;
        }
        $location = $this->fetchOne(
            'SELECT nama FROM lokasi WHERE username_admin = :username',
            ['username' => $username]
        );
        return ['username' => $username, 'nama_outlet' => $location['nama'] ?? 'Outlet ' . ucfirst($username)];
    }
}
