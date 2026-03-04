<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;
use App\Support\Crypto;

class CourseDetailController extends Controller
{
    private CourseService $service;

    public function __construct()
    {
        $this->service = new CourseService(new CourseRepository());
    }

    public function show(Request $request, array $params): Response
    {
        $id = $this->decodeId($params['token'] ?? null);
        if ($id === null) {
            return $this->json(['message' => 'Curso inválido'], 404);
        }

        $course = $this->service->find($id);
        if (!$course) {
            return $this->json(['message' => 'Curso não encontrado'], 404);
        }

        return $this->view('pages/course_detail', [
            'course' => $course,
            'userName' => 'XPTO',
        ]);
    }

    private function decodeId(?string $token): ?int
    {
        $plain = Crypto::decrypt($token);
        if ($plain === null || !ctype_digit($plain)) {
            return null;
        }

        return (int) $plain;
    }
}
