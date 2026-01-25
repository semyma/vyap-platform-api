<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Application;

use App\Modules\Platform\Accounts\Domain\DTO\AccountSwitchDTO;
use App\Modules\Platform\Accounts\Domain\Exceptions\AccountAccessDeniedException;
use App\Modules\Platform\Accounts\Domain\Repositories\AccountMembershipRepository;
use App\Modules\Platform\Accounts\Domain\Repositories\ActiveAccountRepository;

final class AccountSwitchService
{
    public function __construct(
        private readonly AccountMembershipRepository $membershipRepo,
        private readonly ActiveAccountRepository $activeRepo,
    ) {}

    public function switch(int $platformUserId, AccountSwitchDTO $dto): void
    {
        if (! $this->membershipRepo->userBelongsToAccount($platformUserId, $dto->accountId)) {
            throw AccountAccessDeniedException::forAccount($dto->accountId);
        }

        $this->activeRepo->setActiveAccount($platformUserId, $dto->accountId);
    }
}
