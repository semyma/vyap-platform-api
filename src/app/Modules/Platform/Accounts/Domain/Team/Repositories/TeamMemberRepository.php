<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Team\Repositories;

interface TeamMemberRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listByAccount(int $accountId): array;

    public function upsertMember(
        int $accountId,
        int $platformUserId,
        string $role,
        bool $active,
        int $invitedByPlatformUserId
    ): int; // returns account_users.id

    /**
     * Returns membership row or null.
     * @return array<string, mixed>|null
     */
    public function findMembership(int $accountId, int $accountUserId): ?array;

    public function updateMembership(int $accountId, int $accountUserId, ?string $role, ?bool $active): void;

    public function deactivateMembership(int $accountId, int $accountUserId): void;

    public function getRoleForUserInAccount(int $accountId, int $platformUserId): ?string;
}
