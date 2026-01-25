<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Application\Team;

use App\Modules\Platform\Accounts\Domain\Team\Exceptions\CannotModifyOwnerException;
use App\Modules\Platform\Accounts\Domain\Team\Exceptions\TeamMemberRoleNotAllowedException;
use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;

final class UpdateTeamMemberService
{
    private const ALLOWED_ROLES = ['owner', 'cashier', 'staff', 'admin'];

    public function __construct(
        private readonly TeamMemberRepository $repo,
    ) {}

    public function update(int $accountId, int $accountUserId, ?string $role, ?bool $active): void
    {
        $m = $this->repo->findMembership($accountId, $accountUserId);

        if ($m === null) {
            // keep it simple: treat as not found
            abort(404, 'Team member not found');
        }

        if ((string)($m['role'] ?? '') === 'owner') {
            throw CannotModifyOwnerException::make();
        }

        $normalizedRole = $role !== null ? strtolower(trim($role)) : null;

        if ($normalizedRole !== null && !in_array($normalizedRole, self::ALLOWED_ROLES, true)) {
            throw TeamMemberRoleNotAllowedException::make($normalizedRole);
        }

        $this->repo->updateMembership($accountId, $accountUserId, $normalizedRole, $active);
    }

    public function remove(int $accountId, int $accountUserId): void
    {
        $m = $this->repo->findMembership($accountId, $accountUserId);

        if ($m === null) {
            abort(404, 'Team member not found');
        }

        if ((string)($m['role'] ?? '') === 'owner') {
            throw CannotModifyOwnerException::make();
        }

        $this->repo->deactivateMembership($accountId, $accountUserId);
    }
}
