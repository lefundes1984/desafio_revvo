<?php

declare(strict_types=1);

namespace App\Support;

class Crypto
{
    private const METHOD = 'AES-256-CBC';
    private const SECRET_KEY = 'a6a94e132de13792ec3f1477dcbeb296574cea25';
    private const SECRET_IV = '1fdd8a43a4fdc777c9b153d2e6561755';

    public static function encrypt(string $plain): ?string
    {
        $key = hash('sha256', self::SECRET_KEY);
        $iv = substr(hash('sha256', self::SECRET_IV), 0, 16);
        $enc = openssl_encrypt($plain, self::METHOD, $key, 0, $iv);

        return $enc === false ? null : base64_encode($enc);
    }

    public static function decrypt(?string $token): ?string
    {
        if (!$token) {
            return null;
        }

        $key = hash('sha256', self::SECRET_KEY);
        $iv = substr(hash('sha256', self::SECRET_IV), 0, 16);
        $dec = openssl_decrypt(base64_decode($token), self::METHOD, $key, 0, $iv);

        return $dec === false ? null : $dec;
    }
}
