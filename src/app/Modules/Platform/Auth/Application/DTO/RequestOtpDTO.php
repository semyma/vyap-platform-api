<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\DTO;

final readonly class RequestOtpDTO
{
    public function __construct(
        public string $countryIso,
        public string $dialCode,
        public string $phone, // normalized digits
        public string $purpose, // login|register (you decide)
    ) {}
}
