<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Controllers\Api\V1\Otp;

use App\Modules\Platform\Auth\Http\Requests\Api\V1\RequestOtpRequest;
use Illuminate\Http\JsonResponse;
use App\Modules\Platform\Auth\Application\DTO\RequestOtpDTO;
use App\Modules\Platform\Auth\Application\Services\Otp\RequestOtpService;

final class RequestOtpController
{
     public function __construct(
        private readonly RequestOtpService $service,
    ) {}

    public function __invoke(RequestOtpRequest $request): JsonResponse
    {
        $dto = new RequestOtpDTO(
            countryIso: (string) $request->string('country_iso'),
            dialCode: (string) $request->string('dial_code'),
            phone: preg_replace('/\D+/', '', (string) $request->string('phone')) ?? '',
            purpose: (string) $request->string('purpose'),
        );

        $out = $this->service->execute($dto);

        return response()->json(['ok' => true] + $out);
    }
}
