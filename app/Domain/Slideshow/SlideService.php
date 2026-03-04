<?php

declare(strict_types=1);

namespace App\Domain\Slideshow;

use App\Core\Validator;
use PDOException;

class SlideService
{
    public function __construct(private readonly SlideRepository $repository)
    {
    }

    /**
     * @return Slide[]
     */
    public function list(): array
    {
        return $this->repository->all();
    }

    public function create(array $payload): array
    {
        $errors = Validator::validate($payload, [
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'image_url' => 'required|string',
        ]);

        if ($errors) {
            return ['errors' => $errors];
        }

        $slide = $this->repository->create(
            Slide::fromArray([
                'title' => $payload['title'],
                'description' => $payload['description'],
                'image_url' => $payload['image_url'],
                'position' => (int) ($payload['position'] ?? 0),
            ])
        );

        return ['slide' => $slide];
    }

    public function update(int $id, array $payload): array
    {
        $slides = $this->list();
        $current = array_filter($slides, fn (Slide $slide): bool => $slide->id === $id);
        $slide = $current ? array_values($current)[0] : null;

        if (!$slide) {
            return ['errors' => ['message' => 'Slide não encontrado']];
        }

        $slide->title = $payload['title'] ?? $slide->title;
        $slide->description = $payload['description'] ?? $slide->description;
        $slide->imageUrl = $payload['image_url'] ?? $slide->imageUrl;
        $slide->position = isset($payload['position']) ? (int) $payload['position'] : $slide->position;

        try {
            $this->repository->update($slide);
        } catch (PDOException $e) {
            return ['errors' => ['message' => $e->getMessage()]];
        }

        return ['slide' => $slide];
    }

    public function delete(int $id): array
    {
        $this->repository->delete($id);

        return ['deleted' => true];
    }
}
