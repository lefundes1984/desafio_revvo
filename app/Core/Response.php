<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $status;
    private array $headers;
    private string $content;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function json(array $data, int $status = 200): self
    {
        return new self(json_encode($data, JSON_UNESCAPED_UNICODE), $status, [
            'Content-Type' => 'application/json; charset=utf-8',
        ]);
    }

    public static function from(mixed $data, int $status = 200): self
    {
        if ($data instanceof self) {
            return $data;
        }

        if (is_array($data)) {
            return self::json($data, $status);
        }

        return new self((string) $data, $status);
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function withStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        echo $this->content;
    }
}
