<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TeamMemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'account_user_id' => (int) $this['account_user_id'],
            'platform_user_id' => (int) $this['platform_user_id'],
            'name' => (string) $this['name'],
            'dial_code' => (string) $this['dial_code'],
            'phone' => (string) $this['phone'],
            'role' => (string) $this['role'],
            'active' => (bool) $this['active'],
            'created_at' => (string) $this['created_at'],
            'updated_at' => (string) $this['updated_at'],
        ];
    }
}
