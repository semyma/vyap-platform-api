<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Application\DTO;

final class GrantSubscriptionDTO
{
    public function __construct(
        public readonly int $platformUserId,
        public readonly string $serviceCode,
        public readonly ?int $days,
        public readonly string $source,
    ) {}
}
