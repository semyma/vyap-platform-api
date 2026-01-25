<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Application\Team;

use App\Modules\Platform\Accounts\Domain\Team\DTO\InviteTeamMemberDTO;
use App\Modules\Platform\Accounts\Domain\Team\Exceptions\TeamMemberRoleNotAllowedException;
use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;
use App\Modules\Platform\Auth\Application\Contracts\UserRepository;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;
use Illuminate\Support\Facades\DB;

final class InviteTeamMemberService
{
    private const ALLOWED_ROLES = ['cashier', 'staff', 'admin']; // owner not via invite

    public function __construct(
        private readonly UserRepository $users,
        private readonly TeamMemberRepository $teamRepo,
    ) {}

    public function invite(int $accountId, int $invitedByPlatformUserId, InviteTeamMemberDTO $dto): int
    {
        $role = strtolower(trim($dto->role));

        if (! in_array($role, self::ALLOWED_ROLES, true)) {
            throw TeamMemberRoleNotAllowedException::make($role);
        }

        $phone = new PhoneNumber($dto->dialCode, $dto->phone);

        return DB::transaction(function () use ($accountId, $invitedByPlatformUserId, $phone, $role): int {
            $user = $this->users->findByPhone($phone);

            if ($user === null) {
                $user = $this->users->createUser($phone, name: '');
            }

            return $this->teamRepo->upsertMember(
                accountId: $accountId,
                platformUserId: (int) $user->id,
                role: $role,
                active: true,
                invitedByPlatformUserId: $invitedByPlatformUserId,
            );
        });
    }
}
