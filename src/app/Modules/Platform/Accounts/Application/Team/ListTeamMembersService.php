<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Application\Team;

use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;

final class ListTeamMembersService
{
    public function __construct(
        private readonly TeamMemberRepository $repo,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(int $accountId): array
    {
        return $this->repo->listByAccount($accountId);
    }
}
