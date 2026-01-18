<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Domain\Entities;

use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;

final readonly class AuthUser
{
    public function __construct(
        public int $id,
        public string $name,
        public PhoneNumber $phone,
        public bool $active,
    ) {}
}
