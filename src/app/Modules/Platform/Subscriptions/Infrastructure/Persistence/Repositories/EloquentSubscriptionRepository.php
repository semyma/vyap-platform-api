<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Repositories;

use App\Modules\Platform\Subscriptions\Application\Contracts\SubscriptionRepository;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class EloquentSubscriptionRepository implements SubscriptionRepository
{
    public function hasActiveSubscription(int $platformUserId, string $serviceCode, int $nowEpoch): bool
    {
        return SubscriptionModel::query()
            ->where('platform_user_id', $platformUserId)
            ->where('service_code', $serviceCode)
            ->where('status', 'active')
            ->where(function ($q) use ($nowEpoch) {
                // starts_at is null OR already started
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', date('Y-m-d H:i:s', $nowEpoch));
            })
            ->where(function ($q) use ($nowEpoch) {
                // ends_at is null OR not ended
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', date('Y-m-d H:i:s', $nowEpoch));
            })
            ->exists();
    }


    public function hasActiveSubscriptionForAccount(
    int $accountId,
    string $serviceCode,
    int $nowEpoch
): bool {
    return SubscriptionModel::query()
        ->where('account_id', $accountId)
        ->where('service_code', $serviceCode)
        ->where('status', 'active')
        ->where(function ($q) use ($nowEpoch) {
            $q->whereNull('ends_at')
              ->orWhere('ends_at', '>', \Carbon\Carbon::createFromTimestamp($nowEpoch));
        })
        ->exists();
}


    public function listActiveServiceCodes(int $platformUserId, int $nowEpoch): array
    {
        return SubscriptionModel::query()
            ->where('platform_user_id', $platformUserId)
            ->where('status', 'active')
            ->where(function ($q) use ($nowEpoch) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', date('Y-m-d H:i:s', $nowEpoch));
            })
            ->where(function ($q) use ($nowEpoch) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', date('Y-m-d H:i:s', $nowEpoch));
            })
            ->pluck('service_code')
            ->map(fn ($v) => (string) $v)
            ->values()
            ->all();
    }
}
