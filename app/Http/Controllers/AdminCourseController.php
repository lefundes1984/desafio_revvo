<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;

class AdminCourseController extends Controller
{
    private CourseService $service;

    public function __construct()
    {
        $this->service = new CourseService(new CourseRepository());
    }

    public function index(Request $request): Response
    {
        return $this->renderPage();
    }

    public function store(Request $request): Response
    {
        $files = $request->files();
        $coverUpload = $files['cover_image'] ?? null;
        $slideUpload = $files['slide_image'] ?? null;
        $coverPath = $this->handleUpload($coverUpload, 'cover');
        $slidePath = $this->handleUpload($slideUpload, 'slide');

        $payload = [
            'title' => trim((string) $request->input('title', '')),
            'description' => trim((string) $request->input('description', '')),
            'cover_url' => $coverPath ?? trim((string) ($request->input('cover_url') ?? '')),
            'slide_image_url' => $slidePath ?? null,
        ];

        $errors = [];
        if (!$coverPath) {
            $errors['cover_url'][] = 'Imagem de capa é obrigatória.';
        }
        if (!$slidePath) {
            $errors['slide_image_url'][] = 'Imagem do slide é obrigatória.';
        }
        if ($errors) {
            return $this->renderPage($payload, $errors, null, 422);
        }

        $result = $this->service->create($payload);
        if (!empty($result['errors'])) {
            return $this->renderPage($payload, $result['errors'], null, 422);
        }

        $flash = [
            'type' => 'success',
            'message' => 'Curso cadastrado com sucesso.',
        ];

        return $this->renderPage([], [], $flash);
    }

    private function renderPage(array $old = [], array $errors = [], ?array $flash = null, int $status = 200): Response
    {
        $courses = $this->service->list();

        return $this->view('pages/admin_courses', [
            'courses' => $courses,
            'errors' => $errors,
            'old' => $old,
            'flash' => $flash,
        ], $status);
    }

    private function handleUpload(?array $file, string $prefix): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $isImage = str_starts_with((string) ($file['type'] ?? ''), 'image/');
        if (!$isImage) {
            return null;
        }

        $uploadDir = dirname(__DIR__, 3) . '/public/assets/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $ext = pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = sprintf('%s-%s.%s', $prefix, bin2hex(random_bytes(4)), $ext);
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
            return null;
        }

        return '/assets/uploads/' . $filename;
    }
}
