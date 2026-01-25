<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateDeliveryOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'source_bill_id' => ['nullable','integer','min:1'],
            'customer_name' => ['required','string','max:190'],
            'customer_phone' => ['required','string','max:30'],
            'address_line' => ['required','string','max:500'],
            'pincode' => ['nullable','string','max:20'],
            'amount_to_collect' => ['required','numeric','min:0'],
        ];
    }
}
