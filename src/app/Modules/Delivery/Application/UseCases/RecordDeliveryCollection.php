<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\UseCases;

use App\Modules\Delivery\Application\DTOs\RecordCollectionDTO;
use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;
use App\Modules\Delivery\Domain\Exceptions\DeliveryNotFoundException;

final class RecordDeliveryCollection
{
    public function __construct(private readonly DeliveryOrderRepository $repo) {}

    public function execute(int $accountId, int $deliveryId, RecordCollectionDTO $dto): void
    {
        $existing = $this->repo->findForAccount($accountId, $deliveryId);
        if (!$existing) {
            throw DeliveryNotFoundException::forId($deliveryId);
        }

        $this->repo->recordCollection($accountId, $deliveryId, $dto);
    }
}
