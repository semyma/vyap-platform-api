<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\DTOs;

final class AssignDeliveryDTO
{
    public function __construct(
        public readonly int $assignedAccountUserId,
        public readonly ?string $notes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            assignedAccountUserId: (int)$data['assigned_account_user_id'],
            notes: $data['notes'] ?? null,
        );
    }
}
