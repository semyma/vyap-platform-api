<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Controllers\Api\V1\Me;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Platform\Accounts\Infrastructure\Persistence\Models\AccountUserModel;

final class MeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'code' => 'UNAUTHENTICATED',
            ], 401);
        }

        // Resolve active account context
        $membership = AccountUserModel::query()
            ->with('account')
            ->where('platform_user_id', (int) $user->id)
            ->where('active', true)
            ->first();

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => (int) $user->id,
                'dial_code' => (string) $user->dial_code,
                'phone' => (string) $user->phone,
                'roles' => $user->getRoleNames()->values()->all(),
            ],
            'account' => $membership === null ? null : [
                'id' => (int) $membership->account->id,
                'name' => (string) ($membership->account->name ?? ''),
                'role' => (string) $membership->account_role,
            ],
        ]);
    }
}
