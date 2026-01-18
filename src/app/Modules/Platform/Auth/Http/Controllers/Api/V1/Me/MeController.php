<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Controllers\Api\V1\Me;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => (int) $user->getAuthIdentifier(),
                'name' => (string) ($user->name ?? ''),
                'dial_code' => (string) ($user->dial_code ?? ''),
                'phone' => (string) ($user->phone ?? ''),
                'active' => (bool) ($user->active ?? false),
                'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames()->values() : [],
            ],
        ]);
    }
}
