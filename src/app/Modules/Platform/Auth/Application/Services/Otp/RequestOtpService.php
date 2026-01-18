<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Services\Otp;

use App\Modules\Platform\Auth\Application\Contracts\OtpChallengeRepository;
use App\Modules\Platform\Auth\Application\DTO\RequestOtpDTO;
use App\Modules\Platform\Auth\Domain\Entities\OtpChallenge;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;

final class RequestOtpService
{
    public function __construct(
        private readonly OtpChallengeRepository $otpChallenges,
    ) {}

    public function execute(RequestOtpDTO $dto): array
    {
        $phone = new PhoneNumber($dto->dialCode, $dto->phone);

        $token = bin2hex(random_bytes(16)); // 32 chars
$otp = (string) random_int(100000, 999999);
$otpHash = password_hash($otp, PASSWORD_BCRYPT);
$expiresAt = time() + 300;

        $challenge = new OtpChallenge(
            token: $token,
            phone: $phone,
            purpose: $dto->purpose,
            otpHash: $otpHash,
            expiresAtEpoch: $expiresAt,
            used: false,
        );

        $this->otpChallenges->create($challenge);

     return [
    'sent' => true,
    'expires_in_seconds' => 300,
    'dev_otp' => app()->environment('local') ? $otp : null,
];


    }
}
