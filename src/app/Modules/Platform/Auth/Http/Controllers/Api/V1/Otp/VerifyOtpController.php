<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Http\Controllers\Api\V1\Otp;

use App\Modules\Platform\Auth\Http\Requests\Api\V1\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use App\Modules\Platform\Auth\Application\DTO\VerifyOtpDTO;
use App\Modules\Platform\Auth\Application\Services\Otp\VerifyOtpService;


final class VerifyOtpController
{
     public function __construct(
        private readonly VerifyOtpService $service,
    ) {}

      public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        $dto = new VerifyOtpDTO(
            dialCode: (string) $request->string('dial_code'),
            phone: preg_replace('/\D+/', '', (string) $request->string('phone')) ?? '',
            otp: (string) $request->string('otp'),
            
        );

        $out = $this->service->execute($dto);

        return response()->json(['ok' => true] + $out);
    }
}
