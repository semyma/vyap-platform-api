<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Application\Services\Otp;

use App\Modules\Platform\Auth\Application\Contracts\OtpChallengeRepository;
use App\Modules\Platform\Auth\Application\Contracts\UserRepository;
use App\Modules\Platform\Auth\Application\DTO\VerifyOtpDTO;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpAlreadyUsedException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpExpiredException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpInvalidException;
use App\Modules\Platform\Auth\Domain\Exceptions\OtpNotFoundException;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Models\PlatformUserModel;
use App\Modules\Platform\Accounts\Infrastructure\Persistence\Models\AccountModel;
use App\Modules\Platform\Accounts\Infrastructure\Persistence\Models\AccountUserModel;

final class VerifyOtpService
{
    public function __construct(
        private readonly OtpChallengeRepository $otpChallenges,
        private readonly UserRepository $users,
    ) {}

    public function execute(VerifyOtpDTO $dto): array
    {
        $phone = new PhoneNumber(
            dialCode: $dto->dialCode,
            nationalNumber: $dto->phone,
        );

        // Find latest active (not used, not expired) OTP challenge for this phone
        $challenge = $this->otpChallenges->findLatestActiveByPhone($phone);

        if ($challenge === null) {
            throw new OtpNotFoundException('OTP not found');
        }

        // Defensive checks (should already be filtered by repository, but ok to keep)
        if ($challenge->used) {
            throw new OtpAlreadyUsedException('OTP already used');
        }

        if ($challenge->isExpired(time())) {
            throw new OtpExpiredException('OTP expired');
        }

        if (!password_verify($dto->otp, $challenge->otpHash)) {
            throw new OtpInvalidException('Invalid OTP');
        }

        // Mark OTP as used AFTER successful verification
        $this->otpChallenges->markUsed($challenge->token);

        // Create or fetch user
       $existingUser = $this->users->findByPhone($phone);

if ($existingUser === null) {
    // ✅ First-time login: create platform user
    $user = $this->users->createUser($phone);

    // ✅ Create Account (workspace)
    $account = AccountModel::create([
        'name' => null, // filled later via onboarding
    ]);

    // ✅ Attach user as account OWNER
    AccountUserModel::create([
        'account_id' => $account->id,
        'platform_user_id' => $user->id,
        'account_role' => 'owner',
        'active' => true,
    ]);
} else {
    $user = $existingUser;
}


        /** @var PlatformUserModel $model */
        $model = PlatformUserModel::query()->findOrFail($user->id);

        // Default RBAC: assign minimal role if missing
        if ($model->getRoleNames()->isEmpty()) {
            $model->assignRole('platform-user');
        }

        // Optional bootstrap admin by env-configured phone (local/dev)
        $adminPhone = (string) config('vyap.platform_admin_phone', '');
        $adminPhone = preg_replace('/\D+/', '', $adminPhone) ?? '';

        if ($adminPhone !== '' && $adminPhone === $dto->phone) {
            $model->syncRoles(['platform-admin']);
        }

        // Single-session login: revoke old tokens
        $model->tokens()->delete();

        $plain = $model->createToken('api')->plainTextToken;

        return [
            'verified' => true,
            'token' => $plain,
            'token_type' => 'Bearer',
        ];
    }
}
