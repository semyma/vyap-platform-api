<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Repositories;

interface AccountMembershipRepository
{
    public function userBelongsToAccount(int $platformUserId, int $accountId): bool;

    /**
     * @return array<int, array{id:int,name:string,role:string,membership_active:bool}>
     */
    public function listAccountsForUser(int $platformUserId): array;

    public function getUserRoleInAccount(int $platformUserId, int $accountId): ?string;
}
