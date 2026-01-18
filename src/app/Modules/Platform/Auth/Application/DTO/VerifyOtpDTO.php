<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\DTO;

final readonly class VerifyOtpDTO
{
    public function __construct(
        public string $dialCode,
        public string $phone,
        public string $otp,
        public string $otpToken, // server-issued token from request step
    ) {}
}
