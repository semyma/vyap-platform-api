<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\DTO;

final class AccountSwitchDTO
{
    public function __construct(
        public readonly int $accountId,
    ) {}
}
