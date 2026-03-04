<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;
use App\Domain\Slideshow\SlideRepository;
use App\Domain\Slideshow\SlideService;
use App\Support\Crypto;
use App\Core\Request;
use App\Core\Response;

class HomeController extends Controller
{
    private SlideService $slides;
    private CourseService $courses;

    public function __construct()
    {
        $this->slides = new SlideService(new SlideRepository());
        $this->courses = new CourseService(new CourseRepository());
    }

    public function index(Request $request): Response
    {
        $slides = $this->slides->list();
        $courses = array_map(function ($course) {
            $course->token = Crypto::encrypt((string) $course->id);
            return $course;
        }, $this->courses->list());

        return $this->view('pages/home', [
            'slides' => $slides,
            'courses' => $courses,
            'userName' => 'XPTO',
        ]);
    }
}
