<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Store;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class ListServicesController
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

        $services = (array) config('vyap_services.services', []);
        $services = array_values(array_filter($services, static fn ($s) => (bool) ($s['enabled'] ?? false)));

        // Load user subscriptions (only once)
        $subs = SubscriptionModel::query()
            ->where('platform_user_id', (int) $user->id)
            ->get()
            ->keyBy('service_code');

        $now = now();

        $out = array_map(static function (array $svc) use ($subs, $now): array {
            $code = (string) ($svc['code'] ?? '');

            /** @var SubscriptionModel|null $sub */
            $sub = $subs->get($code);

            $isActive = false;
            if ($sub !== null && (string) $sub->status === 'active') {
                $startsOk = $sub->starts_at === null || $sub->starts_at->lte($now);
                $endsOk = $sub->ends_at === null || $sub->ends_at->gt($now);
                $isActive = $startsOk && $endsOk;
            }

            $daysLeft = null;

if ($sub !== null && $sub->ends_at !== null) {
    // Use start-of-day to avoid UI confusion with hours/minutes
    $daysLeft = max(0, $now->startOfDay()->diffInDays($sub->ends_at->startOfDay(), false));
}

$cta = 'buy';
if ($isActive) {
    $cta = 'open';

    // If subscription ends soon, suggest renew
    if ($daysLeft !== null && $daysLeft <= 7) {
        $cta = 'renew';
    }
} else {
    // If user has a record but not active (expired/cancelled), renewal makes sense
    if ($sub !== null) {
        $cta = 'renew';
    }
}


            return [
                'code' => $code,
                'name' => (string) ($svc['name'] ?? ''),
                'description' => (string) ($svc['description'] ?? ''),
                'enabled' => (bool) ($svc['enabled'] ?? false),
                'pricing' => $svc['pricing'] ?? null,

                'subscribed' => $isActive,
                'days_left' => $daysLeft,
'cta' => $cta,

                'subscription' => $sub === null ? null : [
                    'status' => (string) $sub->status,
                    'starts_at' => optional($sub->starts_at)->toISOString(),
                    'ends_at' => optional($sub->ends_at)->toISOString(),
                    'source' => (string) $sub->source,
                ],
            ];
        }, $services);

        return response()->json([
            'ok' => true,
            'services' => $out,
        ]);
    }
}
