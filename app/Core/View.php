<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $template, array $data = [], string $layout = 'layouts/app'): string
    {
        $viewPath = self::pathFor($template);
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View {$template} not found.");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        $layoutPath = self::pathFor($layout);
        if (!file_exists($layoutPath)) {
            return (string) $content;
        }

        ob_start();
        include $layoutPath;

        return ob_get_clean() ?: '';
    }

    private static function pathFor(string $template): string
    {
        $basePath = dirname(__DIR__) . '/Views/';
        $normalized = str_replace('.', '/', $template);

        return $basePath . $normalized . '.php';
    }
}
