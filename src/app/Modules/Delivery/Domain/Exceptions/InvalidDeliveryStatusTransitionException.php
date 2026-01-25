<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Exceptions;

use RuntimeException;

final class InvalidDeliveryStatusTransitionException extends RuntimeException
{
    public static function fromTo(string $from, string $to): self
    {
        return new self("Invalid status transition: {$from} -> {$to}");
    }
}
