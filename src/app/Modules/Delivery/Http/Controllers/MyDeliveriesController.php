<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Delivery\Application\UseCases\ListMyAssignedDeliveries;
use App\Modules\Delivery\Application\UseCases\UpdateDeliveryStatus;
use App\Modules\Delivery\Application\DTOs\UpdateDeliveryStatusDTO;
use App\Modules\Delivery\Http\Requests\UpdateDeliveryStatusRequest;
use App\Modules\Delivery\Http\Resources\DeliveryOrderResource;

final class MyDeliveriesController
{
    public function index(Request $request, ListMyAssignedDeliveries $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $accountUserId = (int) $request->attributes->get('active_account_user_id');

        $filters = array_filter([
            'status' => $request->query('status'),
        ]);

        $rows = $uc->execute($accountId, $accountUserId, $filters, (int)$request->query('limit', 50), (int)$request->query('offset', 0));

        return response()->json([
            'ok' => true,
            'data' => DeliveryOrderResource::collection($rows),
        ]);
    }

    public function updateStatus(int $deliveryId, UpdateDeliveryStatusRequest $request, UpdateDeliveryStatus $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');

        $uc->execute($accountId, $deliveryId, UpdateDeliveryStatusDTO::fromArray($request->validated()));

        return response()->json(['ok' => true]);
    }
}
