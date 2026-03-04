<?php

declare(strict_types=1);

namespace App\Domain\Slideshow;

use App\Core\DB;
use PDO;
use PDOException;

class SlideRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DB::connection();
    }

    /**
     * @return Slide[]
     */
    public function all(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT id, title, description, image_url, position FROM slides ORDER BY position ASC, id DESC');
            $rows = $stmt->fetchAll();

            return array_map(fn (array $row): Slide => Slide::fromArray($row), $rows);
        } catch (PDOException) {
            return $this->fallbackSlides();
        }
    }

    public function create(Slide $slide): Slide
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO slides (title, description, image_url, position) VALUES (:title, :description, :image_url, :position)'
        );
        $stmt->execute([
            'title' => $slide->title,
            'description' => $slide->description,
            'image_url' => $slide->imageUrl,
            'position' => $slide->position,
        ]);

        $slide->id = (int) $this->pdo->lastInsertId();

        return $slide;
    }

    public function update(Slide $slide): bool
    {
        if ($slide->id === null) {
            throw new PDOException('Cannot update slide without ID');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE slides SET title = :title, description = :description, image_url = :image_url, position = :position WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $slide->id,
            'title' => $slide->title,
            'description' => $slide->description,
            'image_url' => $slide->imageUrl,
            'position' => $slide->position,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM slides WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    /**
     * @return Slide[]
     */
    private function fallbackSlides(): array
    {
        return [
            new Slide(
                1,
                'Slide 1',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                '/assets/uploads/slide-1-placeholder.svg',
                0
            ),
            new Slide(
                2,
                'Slide 2',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                '/assets/uploads/slide-2-placeholder.svg',
                1
            ),
            new Slide(
                3,
                'Slide 3',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                '/assets/uploads/slide-3-placeholder.svg',
                2
            ),
        ];
    }
}
