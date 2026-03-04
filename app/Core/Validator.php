<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $fieldRules) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $isValid = match ($name) {
                    'required' => self::required($value),
                    'string' => self::string($value),
                    'max' => self::maxLength((string) $value, (int) $param),
                    'numeric' => is_numeric($value),
                    default => true,
                };

                if (!$isValid) {
                    $errors[$field][] = self::message($name, $field, $param);
                }
            }
        }

        return $errors;
    }

    private static function required(mixed $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        return !empty($value);
    }

    private static function string(mixed $value): bool
    {
        return is_string($value);
    }

    private static function maxLength(string $value, int $limit): bool
    {
        return mb_strlen($value) <= $limit;
    }

    private static function message(string $rule, string $field, mixed $param): string
    {
        return match ($rule) {
            'required' => "{$field} é obrigatório.",
            'string' => "{$field} deve ser texto.",
            'max' => "{$field} deve ter no máximo {$param} caracteres.",
            'numeric' => "{$field} deve ser numérico.",
            default => "{$field} é inválido.",
        };
    }
}
