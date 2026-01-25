<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\DTOs;

final class UpdateDeliveryStatusDTO
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $failureReason,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: (string)$data['status'],
            failureReason: $data['failure_reason'] ?? null,
        );
    }
}
