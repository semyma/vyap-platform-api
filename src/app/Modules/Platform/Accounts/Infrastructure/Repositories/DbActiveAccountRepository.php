<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\Platform\Accounts\Domain\Repositories\ActiveAccountRepository;

final class DbActiveAccountRepository implements ActiveAccountRepository
{
    public function setActiveAccount(int $platformUserId, int $accountId): void
    {
        DB::table('platform_users')
            ->where('id', $platformUserId)
            ->update([
                'active_account_id' => $accountId,
                'updated_at' => now(),
            ]);
    }

    public function getActiveAccountId(int $platformUserId): ?int
    {
        $id = DB::table('platform_users')
            ->where('id', $platformUserId)
            ->value('active_account_id');

        return $id !== null ? (int) $id : null;
    }
}
