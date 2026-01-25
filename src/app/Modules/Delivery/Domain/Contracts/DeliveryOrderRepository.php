<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Contracts;

use App\Modules\Delivery\Application\DTOs\AssignDeliveryDTO;
use App\Modules\Delivery\Application\DTOs\CreateDeliveryOrderDTO;
use App\Modules\Delivery\Application\DTOs\RecordCollectionDTO;
use App\Modules\Delivery\Application\DTOs\UpdateDeliveryStatusDTO;
use App\Modules\Delivery\Domain\Entities\DeliveryOrder;

interface DeliveryOrderRepository
{
    public function create(int $accountId, int $createdByAccountUserId, CreateDeliveryOrderDTO $dto): int;

    public function findForAccount(int $accountId, int $deliveryId): ?DeliveryOrder;

    /** @return array<int, DeliveryOrder> */
    public function listForAccount(int $accountId, array $filters = [], int $limit = 50, int $offset = 0): array;

    /** @return array<int, DeliveryOrder> */
    public function listAssignedToMe(int $accountId, int $accountUserId, array $filters = [], int $limit = 50, int $offset = 0): array;

    public function assign(int $accountId, int $deliveryId, AssignDeliveryDTO $dto): void;

    public function updateStatus(int $accountId, int $deliveryId, UpdateDeliveryStatusDTO $dto): void;

    public function recordCollection(int $accountId, int $deliveryId, RecordCollectionDTO $dto): void;
}
