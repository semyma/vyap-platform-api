<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Application\Services\Admin;

use Illuminate\Support\Collection;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class ListSubscriptionsService
{
    /**
     * @return Collection<int, SubscriptionModel>
     */
    public function execute(int $platformUserId, ?string $status): Collection
    {
        $q = SubscriptionModel::query()
            ->where('platform_user_id', $platformUserId)
            ->orderBy('service_code');

        if ($status !== null && $status !== '') {
            $q->where('status', $status);
        }

        return $q->get();
    }
}
