<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Exceptions;

use RuntimeException;

final class UnauthorizedDeliveryActionException extends RuntimeException
{
    public static function action(string $action): self
    {
        return new self("Unauthorized delivery action: {$action}");
    }
}
