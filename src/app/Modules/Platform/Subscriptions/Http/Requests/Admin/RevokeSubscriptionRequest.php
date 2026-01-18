<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class RevokeSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform_user_id' => ['required', 'integer', 'min:1'],
            'service_code'     => ['required', 'string', 'min:2', 'max:50'],
            'reason'           => ['nullable', 'string', 'max:200'],
        ];
    }
}
