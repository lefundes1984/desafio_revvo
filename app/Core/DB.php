<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection) {
            return self::$connection;
        }

        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            if ($driver === 'mysql') {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $database = $_ENV['DB_DATABASE'] ?? 'desafio_revvo';
                $user = $_ENV['DB_USERNAME'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
                self::$connection = new PDO($dsn, $user, $password, $options);
            } else {
                $database = $_ENV['DB_DATABASE'] ?? dirname(__DIR__, 2) . '/storage/database/database.sqlite';
                $dsn = "sqlite:{$database}";
                self::$connection = new PDO($dsn, null, null, $options);
            }
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int) $e->getCode());
        }

        return self::$connection;
    }
}
