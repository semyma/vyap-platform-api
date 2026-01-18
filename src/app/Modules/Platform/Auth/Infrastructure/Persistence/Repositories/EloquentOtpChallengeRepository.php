<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Infrastructure\Persistence\Repositories;

use App\Modules\Platform\Auth\Application\Contracts\OtpChallengeRepository;
use App\Modules\Platform\Auth\Domain\Entities\OtpChallenge;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Models\AuthOtpChallengeModel;

final class EloquentOtpChallengeRepository implements OtpChallengeRepository
{
    public function create(OtpChallenge $challenge): void
    {
        AuthOtpChallengeModel::create([
            'token'      => $challenge->token,
            'dial_code'  => $challenge->phone->dialCode,
            'phone'      => $challenge->phone->nationalNumber,
            'purpose'    => $challenge->purpose,
            'otp_hash'   => $challenge->otpHash,
            'expires_at' => $challenge->expiresAtEpoch, // epoch seconds
            'used'       => $challenge->used,
        ]);
    }

    public function findLatestActiveByPhone(PhoneNumber $phone): ?OtpChallenge
    {
        $row = AuthOtpChallengeModel::query()
            ->where('dial_code', $phone->dialCode)
            ->where('phone', $phone->nationalNumber)
            ->where('used', false)
            ->where('expires_at', '>', time()) // column is expires_at (epoch seconds)
            ->orderByDesc('id')
            ->first();

        if ($row === null) {
            return null;
        }

        return new OtpChallenge(
            token: (string) $row->token,
            phone: new PhoneNumber(
                dialCode: (string) $row->dial_code,
                nationalNumber: (string) $row->phone,
            ),
            purpose: (string) $row->purpose,
            otpHash: (string) $row->otp_hash,
            expiresAtEpoch: (int) $row->expires_at,
            used: (bool) $row->used,
        );
    }

    public function findByToken(string $token): ?OtpChallenge
    {
        $row = AuthOtpChallengeModel::query()
            ->where('token', $token)
            ->first();

        if ($row === null) {
            return null;
        }

        return new OtpChallenge(
            token: (string) $row->token,
            phone: new PhoneNumber(
                dialCode: (string) $row->dial_code,
                nationalNumber: (string) $row->phone,
            ),
            purpose: (string) $row->purpose,
            otpHash: (string) $row->otp_hash,
            expiresAtEpoch: (int) $row->expires_at,
            used: (bool) $row->used,
        );
    }

    public function markUsed(string $token): void
    {
        AuthOtpChallengeModel::query()
            ->where('token', $token)
            ->update(['used' => true]);
    }
}
