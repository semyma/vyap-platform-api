<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Requests\Api\V1;;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dial_code'  => ['required', 'string', 'max:8'],
            'phone'      => ['required', 'string', 'min:6', 'max:15'],
            'otp'        => ['required', 'string', 'min:4', 'max:8'],
            'otp_token'  => ['required', 'string', 'min:20', 'max:200'],
        ];
    }
}
