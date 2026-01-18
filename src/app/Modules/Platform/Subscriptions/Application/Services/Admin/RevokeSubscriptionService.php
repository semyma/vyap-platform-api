<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Application\Services\Admin;

use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class RevokeSubscriptionService
{
    public function execute(int $platformUserId, string $serviceCode): ?SubscriptionModel
    {
        /** @var SubscriptionModel|null $sub */
        $sub = SubscriptionModel::query()
            ->where('platform_user_id', $platformUserId)
            ->where('service_code', $serviceCode)
            ->first();

        if ($sub === null) {
            return null;
        }

        $sub->status = 'cancelled';
        $sub->ends_at = now();
        $sub->save();

        return $sub;
    }
}
