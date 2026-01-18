<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class GrantSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // guarded by role middleware at route level
    }

    public function rules(): array
    {
        return [
            'platform_user_id' => ['required', 'integer', 'min:1'],
            'service_code'     => ['required', 'string', 'min:2', 'max:50'],
            'days'             => ['nullable', 'integer', 'min:1', 'max:3650'],
            'source'           => ['nullable', 'string', 'max:30'],
        ];
    }
}
