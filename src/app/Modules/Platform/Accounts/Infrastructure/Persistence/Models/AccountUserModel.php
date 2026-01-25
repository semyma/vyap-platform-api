<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Models\PlatformUserModel;

final class AccountUserModel extends Model
{
    protected $table = 'account_users';

    protected $fillable = [
        'account_id',
        'platform_user_id',
        'account_role',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountModel::class, 'account_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlatformUserModel::class, 'platform_user_id');
    }
}
