<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Course\Course;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testCourseTransformsToAndFromArray(): void
    {
        $data = [
            'id' => 1,
            'title' => 'Liderança na prática',
            'description' => 'Curso de liderança para gestores iniciantes.',
            'price' => 199.9,
            'cover_url' => '/assets/uploads/lideranca.jpg',
        ];

        $course = Course::fromArray($data);
        $this->assertSame($data['id'], $course->id);
        $this->assertSame($data['title'], $course->title);
        $this->assertSame($data['description'], $course->description);
        $this->assertSame($data['price'], $course->price);
        $this->assertSame($data['cover_url'], $course->coverUrl);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Liderança na prática',
            'description' => 'Curso de liderança para gestores iniciantes.',
            'price' => 199.9,
            'coverUrl' => '/assets/uploads/lideranca.jpg',
        ], $course->toArray());
    }
}
