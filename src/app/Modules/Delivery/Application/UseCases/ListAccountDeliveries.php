<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\UseCases;

use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;

final class ListAccountDeliveries
{
    public function __construct(private readonly DeliveryOrderRepository $repo) {}

    public function execute(int $accountId, array $filters = [], int $limit = 50, int $offset = 0): array
    {
        return $this->repo->listForAccount($accountId, $filters, $limit, $offset);
    }
}
