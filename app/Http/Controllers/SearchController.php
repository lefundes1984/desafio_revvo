<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;
use App\Support\Crypto;

class SearchController extends Controller
{
    private CourseService $courses;

    public function __construct()
    {
        $this->courses = new CourseService(new CourseRepository());
    }

    public function index(Request $request): Response
    {
        $query = trim((string) $request->query('q', ''));
        $results = $query !== '' ? $this->courses->search($query) : [];

        $courses = array_map(function ($course) {
            $course->token = Crypto::encrypt((string) $course->id);

            return $course;
        }, $results);

        return $this->view('pages/search_results', [
            'query' => $query,
            'courses' => $courses,
            'resultsCount' => count($courses),
            'userName' => 'XPTO',
        ]);
    }
}
