<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Services;

use App\Models\User;

final class AssignDefaultRoleService
{
    public function assignIfMissing(User $user): void
    {
        if ($user->roles()->exists()) {
            return;
        }

        $user->assignRole('platform-user');
    }
}
