<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Domain\ValueObjects;

final readonly class PhoneNumber
{
    public function __construct(
        public string $dialCode,
        public string $nationalNumber
    ) {}

    public function e164(): string
    {
        $dial = str_starts_with($this->dialCode, '+') ? $this->dialCode : ('+' . $this->dialCode);
        return $dial . $this->nationalNumber;
    }
}
