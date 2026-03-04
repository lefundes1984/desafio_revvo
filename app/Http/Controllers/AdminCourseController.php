<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Course\CourseRepository;
use App\Domain\Course\CourseService;
use App\Support\Crypto;

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

    public function edit(Request $request, array $params): Response
    {
        $id = $this->decodeId($params['token'] ?? null);
        if ($id === null) {
            return $this->renderPage([], ['message' => ['Curso inválido']], null, 404);
        }

        $course = $this->service->find($id);
        if (!$course) {
            return $this->renderPage([], ['message' => ['Curso não encontrado']], null, 404);
        }

        return $this->renderPage([
            'title' => $course->title,
            'description' => $course->description,
            'cover_url' => $course->coverUrl,
            'slide_image_url' => $course->slideImageUrl,
            'is_featured' => $course->isFeatured,
            'id_token' => Crypto::encrypt((string) $course->id),
            'mode' => 'edit',
        ]);
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
            'is_featured' => $request->input('is_featured') ? 1 : 0,
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
            return $this->respond($request, ['errors' => $result['errors']], 422, $payload);
        }

        $flash = ['type' => 'success', 'message' => 'Curso cadastrado com sucesso.'];

        return $this->respond($request, ['message' => $flash['message']], 201, [], $flash);
    }

    public function update(Request $request, array $params): Response
    {
        $id = $this->decodeId($params['token'] ?? null);
        if ($id === null) {
            return $this->respond($request, ['message' => 'Curso inválido'], 404);
        }

        $files = $request->files();
        $coverUpload = $files['cover_image'] ?? null;
        $slideUpload = $files['slide_image'] ?? null;

        $payload = [
            'is_featured' => $request->input('is_featured') ? 1 : 0,
        ];

        $title = trim((string) $request->input('title', ''));
        if ($title !== '') {
            $payload['title'] = $title;
        }

        $description = trim((string) $request->input('description', ''));
        if ($description !== '') {
            $payload['description'] = $description;
        }

        $coverPath = $this->handleUpload($coverUpload, 'cover');
        $slidePath = $this->handleUpload($slideUpload, 'slide');
        if ($coverPath) {
            $payload['cover_url'] = $coverPath;
        }
        if ($slidePath) {
            $payload['slide_image_url'] = $slidePath;
        }
        $featured = $request->input('is_featured');
        if ($featured !== null) {
            $payload['is_featured'] = $featured ? 1 : 0;
        }

        $result = $this->service->update($id, $payload);
        if (!empty($result['errors'])) {
            return $this->respond($request, ['errors' => $result['errors']], 422, $payload + ['mode' => 'edit', 'id_token' => $params['token'] ?? null]);
        }

        $flash = ['type' => 'success', 'message' => 'Curso atualizado com sucesso.'];

        return $this->respond($request, ['message' => $flash['message']], 200, [], $flash);
    }

    public function destroy(Request $request, array $params): Response
    {
        $id = $this->decodeId($params['token'] ?? null);
        if ($id === null) {
            return $this->json(['message' => 'Curso inválido'], 400);
        }

        $this->service->delete($id);

        return $this->json(['deleted' => true]);
    }

    private function renderPage(array $old = [], array $errors = [], ?array $flash = null, int $status = 200): Response
    {
        $courses = $this->service->list();
        $courses = array_map(function ($course) {
            $course->id = $course->id;
            $course->token = Crypto::encrypt((string) $course->id);

            return $course;
        }, $courses);

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

    private function decodeId(?string $token): ?int
    {
        $plain = Crypto::decrypt($token);
        if ($plain === null || !ctype_digit($plain)) {
            return null;
        }

        return (int) $plain;
    }

    private function respond(Request $request, array $payload, int $status = 200, array $old = [], ?array $flash = null): Response
    {
        $isAjax = strtolower((string) $request->header('x-requested-with', '')) === 'xmlhttprequest'
            || str_contains((string) $request->header('accept', ''), 'application/json');

        if ($isAjax) {
            return $this->json($payload, $status);
        }

        return $this->renderPage($old, $payload['errors'] ?? [], $flash, $status);
    }
}
