<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\DTOs;

final class CreateDeliveryOrderDTO
{
    public function __construct(
        public readonly ?int $sourceBillId,
        public readonly string $customerName,
        public readonly string $customerPhone,
        public readonly string $addressLine,
        public readonly ?string $pincode,
        public readonly float $amountToCollect,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sourceBillId: isset($data['source_bill_id']) ? (int)$data['source_bill_id'] : null,
            customerName: (string)$data['customer_name'],
            customerPhone: (string)$data['customer_phone'],
            addressLine: (string)$data['address_line'],
            pincode: $data['pincode'] ?? null,
            amountToCollect: (float)$data['amount_to_collect'],
        );
    }
}
