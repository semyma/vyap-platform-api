<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Application;

use App\Modules\Platform\Accounts\Domain\Repositories\AccountMembershipRepository;
use App\Modules\Platform\Accounts\Domain\Repositories\ActiveAccountRepository;
use App\Modules\Platform\Accounts\Domain\Exceptions\NoActiveAccountException;

final class ActiveAccountResolver
{
    public function __construct(
        private readonly ActiveAccountRepository $activeRepo,
        private readonly AccountMembershipRepository $membershipRepo,
    ) {}

    public function resolveActiveAccountId(int $platformUserId): int
    {
        $activeId = $this->activeRepo->getActiveAccountId($platformUserId);

        if ($activeId === null) {
            throw NoActiveAccountException::make();
        }

        // Safety: if membership is removed/disabled, active account becomes invalid
        if (! $this->membershipRepo->userBelongsToAccount($platformUserId, $activeId)) {
            throw NoActiveAccountException::make();
        }

        return $activeId;
    }
}
