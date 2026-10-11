<?php

namespace App\Services;

/**
 * LayananOwner.
 * Menyimpan logika autentikasi owner dan perhitungan data dashboard.
 * Kredensial owner mengikuti sistem native lama, namun disimpan
 * pada berkas konfigurasi agar tidak tersebar di dalam controller.
 */
class LayananOwner
{
    /**
     * Memeriksa apakah kredensial owner valid.
     */
    public function kredensialValid(string $username, string $password): bool
    {
        $userBenar = (string) config('dfc.owner_username');
        $passBenar = (string) config('dfc.owner_password');

        return hash_equals($userBenar, $username) && hash_equals($passBenar, $password);
    }
}
