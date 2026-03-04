<?php

declare(strict_types=1);

namespace App\Domain\Slideshow;

class Slide
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public string $imageUrl,
        public int $position = 0,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $defaultDescription = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';

        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) ($data['title'] ?? 'Slide'),
            (string) ($data['description'] ?? $defaultDescription),
            (string) ($data['image_url'] ?? $data['imageUrl'] ?? ''),
            isset($data['position']) ? (int) $data['position'] : 0
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'imageUrl' => $this->imageUrl,
            'position' => $this->position,
        ];
    }
}
