<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class ListSubscriptionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // admin middleware guards route
    }

    public function rules(): array
    {
        return [
            'platform_user_id' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:30'], // optional filter
        ];
    }
}
