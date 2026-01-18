<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Store;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class MyServicesController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'code' => 'UNAUTHENTICATED',
                'message' => 'Authentication required',
            ], 401);
        }

        $rows = SubscriptionModel::query()
            ->where('platform_user_id', (int) $user->id)
            ->orderBy('service_code')
            ->get();

        return response()->json([
            'ok' => true,
            'subscriptions' => $rows->map(static fn ($s) => [
                'service_code' => (string) $s->service_code,
                'status' => (string) $s->status,
                'starts_at' => optional($s->starts_at)->toISOString(),
                'ends_at' => optional($s->ends_at)->toISOString(),
                'source' => (string) $s->source,
            ])->values()->all(),
        ]);
    }
}
