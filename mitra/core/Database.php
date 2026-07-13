<?php
/**
 * Class Database
 * Membungkus koneksi PDO menggunakan pola Singleton
 * agar koneksi hanya dibuat sekali per request.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
                // Paksa timezone MySQL mengikuti WIB, sama seperti versi lama
                self::$instance->exec("SET time_zone = '+07:00'");
            } catch (PDOException $e) {
                die("Koneksi database gagal. Silakan hubungi administrator.");
            }
        }

        return self::$instance;
    }
}
