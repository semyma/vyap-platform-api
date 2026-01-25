<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class InviteTeamMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dial_code' => ['required', 'string', 'max:6'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'string', 'max:50'], // validated in service
        ];
    }
}
