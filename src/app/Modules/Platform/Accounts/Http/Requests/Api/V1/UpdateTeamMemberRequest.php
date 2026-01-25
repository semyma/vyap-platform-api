<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeamMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'boolean'],
        ];
    }
}
