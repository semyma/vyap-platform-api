<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Models\PlatformUserModel;
use App\Modules\Platform\Accounts\Infrastructure\Persistence\Models\AccountModel;

final class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'platform_user_id',
        'service_code',
        'status',
        'starts_at',
        'ends_at',
        'source',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlatformUserModel::class, 'platform_user_id');
    }
    public function account(): BelongsTo
{
    return $this->belongsTo(AccountModel::class, 'account_id');
}
}
