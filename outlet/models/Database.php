<?php
class Database
{
    private const HOST = 'localhost';
    private const NAME = 'dfc_db';
    private const USERNAME = 'root';
    private const PASSWORD = '';

    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', self::HOST, self::NAME);
            self::$connection = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            self::$connection->exec("SET time_zone = '+07:00'");
        }
        return self::$connection;
    }
}
