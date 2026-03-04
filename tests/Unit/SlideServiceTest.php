<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Slideshow\Slide;
use App\Domain\Slideshow\SlideRepository;
use App\Domain\Slideshow\SlideService;
use PHPUnit\Framework\TestCase;

class SlideServiceTest extends TestCase
{
    public function testListReturnsSlidesFromRepository(): void
    {
        $repository = $this->createMock(SlideRepository::class);
        $repository->method('all')->willReturn([
            new Slide(1, 'Título', 'Descrição', '/img.png', 0),
        ]);

        $service = new SlideService($repository);
        $result = $service->list();

        $this->assertCount(1, $result);
        $this->assertSame('Título', $result[0]->title);
    }

    public function testCreateValidatesPayload(): void
    {
        $repository = $this->createMock(SlideRepository::class);
        $service = new SlideService($repository);

        $response = $service->create([
            'title' => '',
            'description' => '',
            'image_url' => '',
        ]);

        $this->assertArrayHasKey('errors', $response);
    }
}
