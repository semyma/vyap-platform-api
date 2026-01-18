<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

final class AuthOtpChallengeModel extends Model
{
    protected $table = 'auth_otp_challenges';

    protected $fillable = [
        'token',
        'dial_code',
        'phone',
        'purpose',
        'otp_hash',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'used' => 'bool',
        'expires_at' => 'int',
    ];
}
