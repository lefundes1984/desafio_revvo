<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Core\Validator;
use PDOException;

class CourseService
{
    public function __construct(private readonly CourseRepository $repository)
    {
    }

    /**
     * @return Course[]
     */
    public function list(): array
    {
        return $this->repository->all();
    }

    /**
     * @return Course[]
     */
    public function search(string $term): array
    {
        $clean = trim($term);
        if ($clean === '') {
            return [];
        }

        return $this->repository->search($clean);
    }

    public function find(int $id): ?Course
    {
        return $this->repository->find($id);
    }

    public function create(array $payload): array
    {
        $errors = Validator::validate($payload, [
            'title' => 'required|string|max:120',
            'description' => 'required|string|max:500',
        ]);

        if ($errors) {
            return ['errors' => $errors];
        }

        $course = $this->repository->create(
            Course::fromArray([
                'title' => $payload['title'],
                'description' => $payload['description'],
                'price' => 0.0,
                'cover_url' => $payload['cover_url'] ?? null,
                'slide_image_url' => $payload['slide_image_url'] ?? null,
                'is_featured' => isset($payload['is_featured']) ? (int) $payload['is_featured'] : 0,
            ])
        );

        return ['course' => $course];
    }

    public function update(int $id, array $payload): array
    {
        $course = $this->find($id);
        if (!$course) {
            return ['errors' => ['message' => 'Curso não encontrado']];
        }

        $course->title = $payload['title'] ?? $course->title;
        $course->description = $payload['description'] ?? $course->description;
        $course->price = isset($payload['price']) ? (float) $payload['price'] : $course->price;
        $course->coverUrl = $payload['cover_url'] ?? $course->coverUrl;
        $course->slideImageUrl = $payload['slide_image_url'] ?? $course->slideImageUrl;
        if (isset($payload['is_featured'])) {
            $course->isFeatured = (int) $payload['is_featured'];
        }

        try {
            $this->repository->update($course);
        } catch (PDOException $e) {
            return ['errors' => ['message' => $e->getMessage()]];
        }

        return ['course' => $course];
    }

    public function delete(int $id): array
    {
        $course = $this->find($id);
        if (!$course) {
            return ['errors' => ['message' => 'Curso não encontrado']];
        }

        $this->repository->delete($id);

        return ['deleted' => true];
    }
}
