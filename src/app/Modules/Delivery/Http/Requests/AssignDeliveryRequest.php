<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AssignDeliveryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'assigned_account_user_id' => ['required','integer','min:1'],
            'notes' => ['nullable','string','max:500'],
        ];
    }
}
