<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Contracts;

use App\Modules\Platform\Auth\Domain\Entities\OtpChallenge;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;

interface OtpChallengeRepository
{
    public function create(OtpChallenge $challenge): void;

    public function findByToken(string $token): ?OtpChallenge;
    public function findLatestActiveByPhone(PhoneNumber $phone): ?OtpChallenge;

    public function markUsed(string $token): void;
}
