<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Application\Services\Admin;

use App\Modules\Platform\Subscriptions\Application\DTO\GrantSubscriptionDTO;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models\SubscriptionModel;

final class GrantSubscriptionService
{
    public function execute(GrantSubscriptionDTO $dto): SubscriptionModel
    {
        $now = now();

        $endsAt = null;
        if ($dto->days !== null) {
            $endsAt = $now->copy()->addDays($dto->days);
        }

        /** @var SubscriptionModel $sub */
        $sub = SubscriptionModel::query()->updateOrCreate(
            [
                'platform_user_id' => $dto->platformUserId,
                'service_code' => $dto->serviceCode,
            ],
            [
                'status' => 'active',
                'starts_at' => $now,
                'ends_at' => $endsAt,
                'source' => $dto->source,
            ]
        );

        return $sub;
    }
}
