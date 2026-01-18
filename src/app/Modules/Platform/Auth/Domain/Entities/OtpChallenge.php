<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Domain\Entities;

use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;

final readonly class OtpChallenge
{
    public function __construct(
        public string $token,
        public PhoneNumber $phone,
        public string $purpose,
        public string $otpHash,
        public int $expiresAtEpoch,
        public bool $used,
    ) {}

    public function isExpired(int $nowEpoch): bool
    {
        return $nowEpoch > $this->expiresAtEpoch;
    }
}
