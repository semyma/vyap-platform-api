<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Application\DTOs;

final class RecordCollectionDTO
{
    public function __construct(
        public readonly float $collectedAmount,
        public readonly string $collectedVia, // cash|upi|bank|card
        public readonly ?string $collectionRef,
        public readonly ?string $collectedAt, // ISO string or null -> now
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            collectedAmount: (float)$data['collected_amount'],
            collectedVia: (string)$data['collected_via'],
            collectionRef: $data['collection_ref'] ?? null,
            collectedAt: $data['collected_at'] ?? null,
        );
    }
}
