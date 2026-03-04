<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Core\DB;
use PDO;
use PDOException;

class CourseRepository
{
    private PDO $pdo;
    private bool $schemaEnsured = false;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DB::connection();
        $this->ensureTable();
    }

    /**
     * @return Course[]
     */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, title, description, price, cover_url, slide_image_url FROM courses ORDER BY id DESC');
        $rows = $stmt->fetchAll();

        return array_map(fn (array $row): Course => Course::fromArray($row), $rows);
    }

    public function find(int $id): ?Course
    {
        $stmt = $this->pdo->prepare('SELECT id, title, description, price, cover_url, slide_image_url FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Course::fromArray($row) : null;
    }

    public function create(Course $course): Course
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO courses (title, description, price, cover_url, slide_image_url) VALUES (:title, :description, :price, :cover_url, :slide_image_url)'
        );
        $stmt->execute([
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'cover_url' => $course->coverUrl,
            'slide_image_url' => $course->slideImageUrl,
        ]);

        $course->id = (int) $this->pdo->lastInsertId();

        return $course;
    }

    public function update(Course $course): bool
    {
        if ($course->id === null) {
            throw new PDOException('Cannot update course without ID');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE courses SET title = :title, description = :description, price = :price, cover_url = :cover_url, slide_image_url = :slide_image_url WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'cover_url' => $course->coverUrl,
            'slide_image_url' => $course->slideImageUrl,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function ensureTable(): void
    {
        if ($this->schemaEnsured) {
            return;
        }

        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql = match ($driver) {
            'mysql' => 'CREATE TABLE IF NOT EXISTS courses (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price DECIMAL(10,2) NOT NULL DEFAULT 0,
                cover_url TEXT NULL,
                slide_image_url TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;',
            'pgsql' => 'CREATE TABLE IF NOT EXISTS courses (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price NUMERIC(10,2) NOT NULL DEFAULT 0,
                cover_url TEXT NULL,
                slide_image_url TEXT NULL,
                created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
            );',
            default => 'CREATE TABLE IF NOT EXISTS courses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                price REAL NOT NULL DEFAULT 0,
                cover_url TEXT NULL,
                slide_image_url TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );',
        };

        try {
            $this->pdo->exec($sql);
            $this->schemaEnsured = true;
        } catch (PDOException) {
            // Keep schemaEnsured as false so a subsequent attempt can retry.
        }

        $this->ensureColumnExists('slide_image_url', 'TEXT NULL');
    }

    private function ensureColumnExists(string $column, string $definition): void
    {
        try {
            $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            $sql = match ($driver) {
                'mysql' => "ALTER TABLE courses ADD COLUMN IF NOT EXISTS {$column} {$definition}",
                'pgsql' => "ALTER TABLE courses ADD COLUMN IF NOT EXISTS {$column} {$definition}",
                default => "ALTER TABLE courses ADD COLUMN IF NOT EXISTS {$column} {$definition}",
            };
            $this->pdo->exec($sql);
        } catch (PDOException) {
            // ignore; column likely already exists or not supported
        }
    }
}
