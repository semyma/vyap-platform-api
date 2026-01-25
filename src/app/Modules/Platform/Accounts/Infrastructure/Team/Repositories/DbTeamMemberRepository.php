<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Team\Repositories;

use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;
use Illuminate\Support\Facades\DB;

final class DbTeamMemberRepository implements TeamMemberRepository
{
    public function listByAccount(int $accountId): array
    {
        return DB::table('account_users as au')
            ->join('platform_users as pu', 'pu.id', '=', 'au.platform_user_id')
            ->where('au.account_id', $accountId)
            ->select([
                'au.id as account_user_id',
                'au.platform_user_id',
                'au.role',
                'au.active',
                'au.created_at',
                'au.updated_at',
                'pu.dial_code',
                'pu.phone',
                'pu.name',
            ])
            ->orderByDesc('au.id')
            ->get()
            ->map(fn ($r) => [
                'account_user_id' => (int) $r->account_user_id,
                'platform_user_id' => (int) $r->platform_user_id,
                'role' => (string) $r->role,
                'active' => ((int) $r->active) === 1,
                'dial_code' => (string) ($r->dial_code ?? ''),
                'phone' => (string) ($r->phone ?? ''),
                'name' => (string) ($r->name ?? ''),
                'created_at' => (string) ($r->created_at ?? ''),
                'updated_at' => (string) ($r->updated_at ?? ''),
            ])
            ->all();
    }

    public function upsertMember(
        int $accountId,
        int $platformUserId,
        string $role,
        bool $active,
        int $invitedByPlatformUserId
    ): int {
        $existingId = DB::table('account_users')
            ->where('account_id', $accountId)
            ->where('platform_user_id', $platformUserId)
            ->value('id');

        if ($existingId !== null) {
            DB::table('account_users')
                ->where('id', (int) $existingId)
                ->update([
                    'role' => $role,
                    'active' => $active ? 1 : 0,
                    'updated_at' => now(),
                ]);

            return (int) $existingId;
        }

        $id = DB::table('account_users')->insertGetId([
            'account_id' => $accountId,
            'platform_user_id' => $platformUserId,
            'role' => $role,
            'active' => $active ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (int) $id;
    }

    public function findMembership(int $accountId, int $accountUserId): ?array
    {
        $row = DB::table('account_users')
            ->where('account_id', $accountId)
            ->where('id', $accountUserId)
            ->first();

        return $row ? (array) $row : null;
    }

    public function updateMembership(int $accountId, int $accountUserId, ?string $role, ?bool $active): void
    {
        $data = ['updated_at' => now()];

        if ($role !== null) {
            $data['role'] = $role;
        }
        if ($active !== null) {
            $data['active'] = $active ? 1 : 0;
        }

        DB::table('account_users')
            ->where('account_id', $accountId)
            ->where('id', $accountUserId)
            ->update($data);
    }

    public function deactivateMembership(int $accountId, int $accountUserId): void
    {
        DB::table('account_users')
            ->where('account_id', $accountId)
            ->where('id', $accountUserId)
            ->update([
                'active' => 0,
                'updated_at' => now(),
            ]);
    }

    public function getRoleForUserInAccount(int $accountId, int $platformUserId): ?string
    {
        $role = DB::table('account_users')
            ->where('account_id', $accountId)
            ->where('platform_user_id', $platformUserId)
            ->where('active', 1)
            ->value('role');

        return $role !== null ? (string) $role : null;
    }
}
