<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Services\Otp;

use App\Modules\Platform\Auth\Application\Contracts\OtpChallengeRepository;
use App\Modules\Platform\Auth\Application\DTO\VerifyOtpDTO;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpAlreadyUsedException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpExpiredException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpInvalidException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpNotFoundException;
use App\Modules\Platform\Auth\Application\Contracts\UserRepository;


final class VerifyOtpService
{
    public function __construct(
        private readonly OtpChallengeRepository $otpChallenges,
        private readonly UserRepository $users,
    ) {}

    public function execute(VerifyOtpDTO $dto): array
{
    $challenge = $this->otpChallenges->findByToken($dto->otpToken);

    if ($challenge === null) {
        throw new OtpNotFoundException('OTP token not found');
    }

    if ($challenge->used) {
        throw new OtpAlreadyUsedException('OTP already used');
    }

    if ($challenge->isExpired(time())) {
        throw new OtpExpiredException('OTP expired');
    }

    if ($challenge->phone->dialCode !== $dto->dialCode || $challenge->phone->nationalNumber !== $dto->phone) {
    throw new OtpInvalidException('OTP token does not match phone');
}

    if (!password_verify($dto->otp, $challenge->otpHash)) {
        throw new OtpInvalidException('Invalid OTP');
    }

    // Mark OTP as used AFTER successful verification
    $this->otpChallenges->markUsed($dto->otpToken);

    $phone = new \App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber(
    dialCode: $dto->dialCode,
    nationalNumber: $dto->phone,
);

$user = $this->users->findByPhone($phone) ?? $this->users->createUser($phone);

// We need the Eloquent model to create token:
$model = \App\Modules\Platform\Auth\Infrastructure\Persistence\Models\PlatformUserModel::query()
    ->findOrFail($user->id);

   // Default RBAC: assign minimal role if missing
if ($model->getRoleNames()->isEmpty()) {
  $model->assignRole('platform-user');
}

$adminPhone = (string) config('vyap.platform_admin_phone', '');
$adminPhone = preg_replace('/\D+/', '', $adminPhone) ?? '';

if ($adminPhone !== '' && $adminPhone === $dto->phone) {
    $model->syncRoles(['platform-admin']);
}


$model->tokens()->delete();
$plain = $model->createToken('api')->plainTextToken;

    return [
    'verified' => true,
    'token' => $plain,
    'token_type' => 'Bearer',
];
}
}
