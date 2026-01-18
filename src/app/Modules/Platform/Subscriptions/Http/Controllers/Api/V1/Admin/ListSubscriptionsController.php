<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use App\Modules\Platform\Subscriptions\Http\Requests\Admin\ListSubscriptionsRequest;
use App\Modules\Platform\Subscriptions\Application\Services\Admin\ListSubscriptionsService;

final class ListSubscriptionsController
{
    public function __construct(
        private readonly ListSubscriptionsService $service,
    ) {}

    public function __invoke(ListSubscriptionsRequest $request): JsonResponse
    {
        $platformUserId = (int) $request->integer('platform_user_id');
        $status = $request->filled('status') ? (string) $request->string('status') : null;

        $rows = $this->service->execute($platformUserId, $status);

        return response()->json([
            'ok' => true,
            'subscriptions' => $rows->map(static fn ($s) => [
                'platform_user_id' => (int) $s->platform_user_id,
                'service_code' => (string) $s->service_code,
                'status' => (string) $s->status,
                'starts_at' => optional($s->starts_at)->toISOString(),
                'ends_at' => optional($s->ends_at)->toISOString(),
                'source' => (string) $s->source,
                'created_at' => optional($s->created_at)->toISOString(),
                'updated_at' => optional($s->updated_at)->toISOString(),
            ])->values()->all(),
        ]);
    }
}
