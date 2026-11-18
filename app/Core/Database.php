<?php
declare(strict_types=1);
namespace App\Core;

use PDO;

/** Singleton: uma conexão PDO compartilhada durante a requisição. */
final class Database
{
    private static ?PDO $connection = null;
    private function __construct() {}
    private function __clone() {}

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $db = getenv('DB_DATABASE') ?: 'pulse_personal';
            self::$connection = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", getenv('DB_USERNAME') ?: 'pulse', getenv('DB_PASSWORD') ?: '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$connection;
    }
}
