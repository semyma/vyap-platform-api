<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\Platform\Accounts\Domain\Repositories\AccountMembershipRepository;

final class DbAccountMembershipRepository implements AccountMembershipRepository
{
    public function userBelongsToAccount(int $platformUserId, int $accountId): bool
    {
        return DB::table('account_users')
            ->where('platform_user_id', $platformUserId)
            ->where('account_id', $accountId)
            ->where('active', 1)
            ->exists();
    }

    public function listAccountsForUser(int $platformUserId): array
    {
        return DB::table('accounts as a')
            ->join('account_users as au', 'au.account_id', '=', 'a.id')
            ->where('au.platform_user_id', $platformUserId)
            ->where('au.active', 1)
            ->select(['a.id', 'a.name', 'au.role as user_role', 'au.active as membership_active'])
            ->orderBy('a.name')
            ->get()
            ->map(fn ($r) => [
                'id' => (int) $r->id,
                'name' => (string) $r->name,
                'role' => (string) $r->user_role,
                'membership_active' => ((int) $r->membership_active) === 1,
            ])
            ->all();
    }

    public function getUserRoleInAccount(int $platformUserId, int $accountId): ?string
    {
        $role = DB::table('account_users')
            ->where('platform_user_id', $platformUserId)
            ->where('account_id', $accountId)
            ->where('active', 1)
            ->value('role');

        return $role !== null ? (string) $role : null;
    }
}
