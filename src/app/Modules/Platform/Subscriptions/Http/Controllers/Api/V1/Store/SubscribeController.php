<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Controllers\Api\V1\Store;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;
use Illuminate\Support\Carbon;

final class SubscribeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

         if (! app()->environment(['local', 'staging'])) {
        return response()->json([
            'ok' => false,
            'code' => 'STORE_PURCHASE_DISABLED',
            'message' => 'Purchases are not enabled yet',
        ], 403);
    }

        $serviceCode = (string) $request->input('service_code');
        $planCode = (string) $request->input('plan_code');

        if ($serviceCode === '' || $planCode === '') {
            return response()->json([
                'ok' => false,
                'message' => 'service_code and plan_code are required',
            ], 422);
        }

        // 1️⃣ Load service catalog
        $services = (array) config('vyap_services.services', []);
        $service = collect($services)->firstWhere('code', $serviceCode);

        if ($service === null || empty($service['enabled'])) {
            return response()->json([
                'ok' => false,
                'message' => 'Service not available',
            ], 422);
        }

        // 2️⃣ Load plan
        $plans = $service['pricing']['plans'] ?? [];
        $plan = collect($plans)->firstWhere('plan_code', $planCode);

        if ($plan === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid plan',
            ], 422);
        }

        // 3️⃣ Prevent double-active subscription
        $now = now();
        $existing = SubscriptionModel::query()
            ->where('platform_user_id', $user->id)
            ->where('service_code', $serviceCode)
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', $now);
            })
            ->first();

        if ($existing !== null) {
            return response()->json([
                'ok' => false,
                'message' => 'Subscription already active',
            ], 409);
        }

        // 4️⃣ Grant subscription
        $startsAt = $now;
        $endsAt = isset($plan['duration_days'])
            ? $now->copy()->addDays((int) $plan['duration_days'])
            : null;

        $subscription = SubscriptionModel::updateOrCreate(
            [
                'platform_user_id' => $user->id,
                'service_code' => $serviceCode,
            ],
            [
                'status' => 'active',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'source' => 'store_dev', // 🔴 TEMP
            ]
        );

        return response()->json([
            'ok' => true,
            'subscription' => [
                'service_code' => $subscription->service_code,
                'status' => $subscription->status,
                'starts_at' => $subscription->starts_at?->toISOString(),
                'ends_at' => $subscription->ends_at?->toISOString(),
                'source' => $subscription->source,
            ],
        ]);
    }
}
