<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Core\DB;
use PDO;
use PDOException;

class CourseRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DB::connection();
    }

    /**
     * @return Course[]
     */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, title, description, price, cover_url FROM courses ORDER BY id DESC');
        $rows = $stmt->fetchAll();

        return array_map(fn (array $row): Course => Course::fromArray($row), $rows);
    }

    public function find(int $id): ?Course
    {
        $stmt = $this->pdo->prepare('SELECT id, title, description, price, cover_url FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Course::fromArray($row) : null;
    }

    public function create(Course $course): Course
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO courses (title, description, price, cover_url) VALUES (:title, :description, :price, :cover_url)'
        );
        $stmt->execute([
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'cover_url' => $course->coverUrl,
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
            'UPDATE courses SET title = :title, description = :description, price = :price, cover_url = :cover_url WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'cover_url' => $course->coverUrl,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }
}
