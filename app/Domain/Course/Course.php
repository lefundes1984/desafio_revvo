<?php

declare(strict_types=1);

namespace App\Domain\Course;

class Course
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public float $price,
        public ?string $coverUrl = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) ($data['title'] ?? ''),
            (string) ($data['description'] ?? ''),
            (float) ($data['price'] ?? 0),
            $data['cover_url'] ?? $data['coverUrl'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'coverUrl' => $this->coverUrl,
        ];
    }
}
