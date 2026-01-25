<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class SwitchAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'integer', 'min:1'],
        ];
    }
}
