<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\UseCases;

use App\Modules\Delivery\Application\DTOs\UpdateDeliveryStatusDTO;
use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;
use App\Modules\Delivery\Domain\Exceptions\DeliveryNotFoundException;

final class UpdateDeliveryStatus
{
    public function __construct(private readonly DeliveryOrderRepository $repo) {}

    public function execute(int $accountId, int $deliveryId, UpdateDeliveryStatusDTO $dto): void
    {
        $existing = $this->repo->findForAccount($accountId, $deliveryId);
        if (!$existing) {
            throw DeliveryNotFoundException::forId($deliveryId);
        }

        // status transition rules can be enforced here later (D2)
        $this->repo->updateStatus($accountId, $deliveryId, $dto);
    }
}
