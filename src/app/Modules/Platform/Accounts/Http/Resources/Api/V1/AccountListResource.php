<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this['id'],
            'name' => (string) $this['name'],
            'role' => (string) $this['role'],
            'membership_active' => (bool) $this['membership_active'],
        ];
    }
}
