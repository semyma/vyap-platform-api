<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Contracts;

use App\Modules\Platform\Auth\Domain\Entities\AuthUser;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;

interface UserRepository
{
    public function findByPhone(PhoneNumber $phone): ?AuthUser;

    public function createUser(PhoneNumber $phone, string $name = ''): AuthUser;
}
