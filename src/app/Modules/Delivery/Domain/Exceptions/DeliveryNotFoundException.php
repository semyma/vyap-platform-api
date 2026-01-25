<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Exceptions;

use RuntimeException;

final class DeliveryNotFoundException extends RuntimeException
{
    public static function forId(string|int $id): self
    {
        return new self("Delivery order not found: {$id}");
    }
}
