<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\UseCases;

use App\Modules\Delivery\Application\DTOs\CreateDeliveryOrderDTO;
use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;

final class CreateDeliveryOrder
{
    public function __construct(private readonly DeliveryOrderRepository $repo) {}

    public function execute(int $accountId, int $createdByAccountUserId, CreateDeliveryOrderDTO $dto): int
    {
        return $this->repo->create($accountId, $createdByAccountUserId, $dto);
    }
}
