<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;

class CourseController extends Controller
{
    private CourseService $service;

    public function __construct()
    {
        $this->service = new CourseService(new CourseRepository());
    }

    public function index(Request $request): Response
    {
        $courses = array_map(fn ($course) => $course->toArray(), $this->service->list());

        return $this->json(['data' => $courses]);
    }

    public function show(Request $request, array $params): Response
    {
        $course = $this->service->find((int) ($params['id'] ?? 0));
        if (!$course) {
            return $this->json(['message' => 'Curso não encontrado'], 404);
        }

        return $this->json(['data' => $course->toArray()]);
    }

    public function store(Request $request): Response
    {
        $result = $this->service->create($request->all());
        if (!empty($result['errors'])) {
            return $this->json(['errors' => $result['errors']], 422);
        }

        return $this->json(['data' => $result['course']->toArray()], 201);
    }

    public function update(Request $request, array $params): Response
    {
        $result = $this->service->update((int) ($params['id'] ?? 0), $request->all());
        if (!empty($result['errors'])) {
            return $this->json(['errors' => $result['errors']], 422);
        }

        return $this->json(['data' => $result['course']->toArray()]);
    }

    public function destroy(Request $request, array $params): Response
    {
        $this->service->delete((int) ($params['id'] ?? 0));

        return $this->json(['deleted' => true]);
    }
}
