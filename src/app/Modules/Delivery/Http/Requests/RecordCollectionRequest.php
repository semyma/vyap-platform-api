<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RecordCollectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'collected_amount' => ['required','numeric','min:0'],
            'collected_via' => ['required', Rule::in(['cash','upi','bank','card'])],
            'collection_ref' => ['nullable','string','max:100'],
            'collected_at' => ['nullable','date'],
        ];
    }
}
