<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AccountModel extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'name',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(AccountUserModel::class, 'account_id');
    }
}
