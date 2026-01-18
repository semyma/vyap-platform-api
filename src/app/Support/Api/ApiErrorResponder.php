<?php

declare(strict_types=1);

namespace App\Support\Api;

use Illuminate\Http\JsonResponse;

final class ApiErrorResponder
{
    /**
     * @param array<string, mixed> $errors
     */
    public static function fail(string $code, string $message, int $status, array $errors = []): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'code' => $code,
            'message' => $message,
            'errors' => (object) $errors,
        ], $status);
    }
}
