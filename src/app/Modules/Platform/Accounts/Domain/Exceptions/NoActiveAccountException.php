<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Exceptions;

use RuntimeException;

final class NoActiveAccountException extends RuntimeException
{
    public static function make(): self
    {
        return new self('No active account selected.');
    }
}
