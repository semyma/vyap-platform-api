<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Team\Exceptions;

use RuntimeException;

final class CannotModifyOwnerException extends RuntimeException
{
    public static function make(): self
    {
        return new self('Owner membership cannot be modified via this endpoint.');
    }
}
