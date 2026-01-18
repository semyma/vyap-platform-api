<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class RequestOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country_iso' => ['required', 'string', 'size:2'],
            'dial_code'   => ['required', 'string', 'max:8'],
            'phone'       => ['required', 'string', 'min:6', 'max:15'],
            'purpose'     => ['required', 'string', 'in:login,register'],
        ];
    }
}
