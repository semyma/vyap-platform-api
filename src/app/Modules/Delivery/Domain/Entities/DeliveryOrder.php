<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Entities;

final class DeliveryOrder
{
    public function __construct(
        public readonly int $id,
        public readonly int $accountId,
        public readonly ?int $sourceBillId,
        public readonly string $customerName,
        public readonly string $customerPhone,
        public readonly string $addressLine,
        public readonly ?string $pincode,
        public readonly string $status,
        public readonly ?int $assignedAccountUserId,
        public readonly float $amountToCollect,
        public readonly float $collectedAmount,
        public readonly ?string $collectedVia,
        public readonly ?string $collectionRef,
        public readonly ?string $collectedAt,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
