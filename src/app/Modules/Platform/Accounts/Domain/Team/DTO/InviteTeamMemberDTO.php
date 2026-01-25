<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Team\DTO;

final class InviteTeamMemberDTO
{
    public function __construct(
        public readonly string $dialCode,
        public readonly string $phone,
        public readonly string $role, // cashier|staff|admin (owner not allowed from invite API)
    ) {}
}
