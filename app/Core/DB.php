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

        $driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '5432';
            $database = $_ENV['DB_DATABASE'] ?? 'desafio_revvo';
            $user = $_ENV['DB_USERNAME'] ?? 'postgres';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            self::$connection = match ($driver) {
                'mysql' => new PDO(
                    "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
                    $user,
                    $password,
                    $options
                ),
                'sqlite' => new PDO(
                    'sqlite:' . ($_ENV['DB_DATABASE'] ?? dirname(__DIR__, 2) . '/storage/database/database.sqlite'),
                    null,
                    null,
                    $options
                ),
                default => new PDO(
                    "pgsql:host={$host};port={$port};dbname={$database}",
                    $user,
                    $password,
                    $options
                ),
            };
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int) $e->getCode());
        }

        return self::$connection;
    }
}
