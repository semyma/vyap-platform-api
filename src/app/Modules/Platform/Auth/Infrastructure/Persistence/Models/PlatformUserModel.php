<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Infrastructure\Persistence\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

final class PlatformUserModel extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;

    protected $table = 'platform_users';
    protected string $guard_name = 'sanctum';

    protected $fillable = [
        'name',
        'dial_code',
        'phone',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];
}
