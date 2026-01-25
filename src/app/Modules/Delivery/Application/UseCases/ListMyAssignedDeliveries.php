<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\UseCases;

use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;

final class ListMyAssignedDeliveries
{
    public function __construct(private readonly DeliveryOrderRepository $repo) {}

    public function execute(int $accountId, int $accountUserId, array $filters = [], int $limit = 50, int $offset = 0): array
    {
        return $this->repo->listAssignedToMe($accountId, $accountUserId, $filters, $limit, $offset);
    }
}
