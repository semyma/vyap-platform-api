<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Application\Contracts;

interface SubscriptionRepository
{
    public function hasActiveSubscription(int $platformUserId, string $serviceCode, int $nowEpoch): bool;

    /**
     * @return array<int, string> list of service codes (e.g. ["billing","rental"])
     */
    public function listActiveServiceCodes(int $platformUserId, int $nowEpoch): array;

    public function hasActiveSubscriptionForAccount(
    int $accountId,
    string $serviceCode,
    int $nowEpoch
): bool;

}
