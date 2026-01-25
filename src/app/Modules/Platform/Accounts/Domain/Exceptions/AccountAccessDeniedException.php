<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Exceptions;

use RuntimeException;

final class AccountAccessDeniedException extends RuntimeException
{
    public static function forAccount(int $accountId): self
    {
        return new self("Access denied to account {$accountId}");
    }
}
