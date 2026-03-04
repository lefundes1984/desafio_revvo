<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Router;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SlideController;
use Dotenv\Dotenv;

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    spl_autoload_register(function ($class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }

        $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
        $path = __DIR__ . '/../app/' . $relative . '.php';
        if (file_exists($path)) {
            require $path;
        }
    });
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = dirname(__DIR__);
if (file_exists($basePath . '/.env')) {
    Dotenv::createImmutable($basePath)->safeLoad();
}

$request = new Request();
$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->get('/admin/courses', [AdminCourseController::class, 'index']);
$router->post('/admin/courses', [AdminCourseController::class, 'store']);
$router->get('/admin/courses/{token}/edit', [AdminCourseController::class, 'edit']);
$router->post('/admin/courses/{token}', [AdminCourseController::class, 'update']);
$router->delete('/admin/courses/{token}', [AdminCourseController::class, 'destroy']);

$router->get('/api/courses', [CourseController::class, 'index']);
$router->get('/api/courses/{id}', [CourseController::class, 'show']);
$router->post('/api/courses', [CourseController::class, 'store']);
$router->put('/api/courses/{id}', [CourseController::class, 'update']);
$router->delete('/api/courses/{id}', [CourseController::class, 'destroy']);

$router->get('/api/slides', [SlideController::class, 'index']);
$router->post('/api/slides', [SlideController::class, 'store']);
$router->put('/api/slides/{id}', [SlideController::class, 'update']);
$router->delete('/api/slides/{id}', [SlideController::class, 'destroy']);

$response = $router->dispatch($request);
$response->send();
