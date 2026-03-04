<?php

declare(strict_types=1);

namespace App\Http\Middleware;

class AuthStub
{
    public function handle(): bool
    {
        // Ponto de extensão para autenticação futura.
        return true;
    }
}
