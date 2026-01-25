<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Repositories;

interface ActiveAccountRepository
{
    public function setActiveAccount(int $platformUserId, int $accountId): void;

    public function getActiveAccountId(int $platformUserId): ?int;
}
