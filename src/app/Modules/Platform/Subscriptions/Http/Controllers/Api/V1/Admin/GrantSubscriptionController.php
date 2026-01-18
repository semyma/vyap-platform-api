<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use App\Modules\Platform\Subscriptions\Http\Requests\Admin\GrantSubscriptionRequest;
use App\Modules\Platform\Subscriptions\Application\DTO\GrantSubscriptionDTO;
use App\Modules\Platform\Subscriptions\Application\Services\Admin\GrantSubscriptionService;

final class GrantSubscriptionController
{
    public function __construct(
        private readonly GrantSubscriptionService $service,
    ) {}

    public function __invoke(GrantSubscriptionRequest $request): JsonResponse
    {
        $dto = new GrantSubscriptionDTO(
            platformUserId: (int) $request->integer('platform_user_id'),
            serviceCode: (string) $request->string('service_code'),
            days: $request->filled('days') ? (int) $request->integer('days') : null,
            source: (string) ($request->string('source')->toString() ?: 'manual'),
        );

        $sub = $this->service->execute($dto);

        return response()->json([
            'ok' => true,
            'subscription' => [
                'platform_user_id' => (int) $sub->platform_user_id,
                'service_code' => (string) $sub->service_code,
                'status' => (string) $sub->status,
                'starts_at' => optional($sub->starts_at)->toISOString(),
                'ends_at' => optional($sub->ends_at)->toISOString(),
                'source' => (string) $sub->source,
            ],
        ]);
    }
}
