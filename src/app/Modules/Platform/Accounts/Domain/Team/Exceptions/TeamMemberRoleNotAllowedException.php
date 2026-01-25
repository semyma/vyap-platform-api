<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Domain\Team\Exceptions;

use RuntimeException;

final class TeamMemberRoleNotAllowedException extends RuntimeException
{
    public static function make(string $role): self
    {
        return new self("Role '{$role}' is not allowed.");
    }
}
