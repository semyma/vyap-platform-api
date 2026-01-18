<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use App\Modules\Platform\Subscriptions\Http\Requests\Admin\RevokeSubscriptionRequest;
use App\Modules\Platform\Subscriptions\Application\Services\Admin\RevokeSubscriptionService;

final class RevokeSubscriptionController
{
    public function __construct(
        private readonly RevokeSubscriptionService $service,
    ) {}

    public function __invoke(RevokeSubscriptionRequest $request): JsonResponse
    {
        $platformUserId = (int) $request->integer('platform_user_id');
        $serviceCode = (string) $request->string('service_code');

        $sub = $this->service->execute($platformUserId, $serviceCode);

        if ($sub === null) {
            return response()->json([
                'ok' => false,
                'code' => 'SUBSCRIPTION_NOT_FOUND',
                'message' => 'Subscription not found',
            ], 404);
        }

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
