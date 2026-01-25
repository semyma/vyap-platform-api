<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Delivery\Application\UseCases\CreateDeliveryOrder;
use App\Modules\Delivery\Application\UseCases\AssignDeliveryPerson;
use App\Modules\Delivery\Application\UseCases\ListAccountDeliveries;
use App\Modules\Delivery\Application\UseCases\ReassignDeliveryPerson;
use App\Modules\Delivery\Application\UseCases\RecordDeliveryCollection;
use App\Modules\Delivery\Application\DTOs\CreateDeliveryOrderDTO;
use App\Modules\Delivery\Application\DTOs\AssignDeliveryDTO;
use App\Modules\Delivery\Application\DTOs\RecordCollectionDTO;
use App\Modules\Delivery\Http\Requests\CreateDeliveryOrderRequest;
use App\Modules\Delivery\Http\Requests\AssignDeliveryRequest;
use App\Modules\Delivery\Http\Requests\RecordCollectionRequest;
use App\Modules\Delivery\Http\Resources\DeliveryOrderResource;

final class AccountDeliveriesController
{
    public function index(Request $request, ListAccountDeliveries $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $filters = array_filter([
            'status' => $request->query('status'),
        ]);

        $rows = $uc->execute($accountId, $filters, (int)$request->query('limit', 50), (int)$request->query('offset', 0));

        return response()->json([
            'ok' => true,
            'data' => DeliveryOrderResource::collection($rows),
        ]);
    }

    public function store(CreateDeliveryOrderRequest $request, CreateDeliveryOrder $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $accountUserId = (int) $request->attributes->get('active_account_user_id');

        $id = $uc->execute($accountId, $accountUserId, CreateDeliveryOrderDTO::fromArray($request->validated()));

        return response()->json(['ok' => true, 'data' => ['id' => $id]], 201);
    }

    public function assign(int $deliveryId, AssignDeliveryRequest $request, AssignDeliveryPerson $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $uc->execute($accountId, $deliveryId, AssignDeliveryDTO::fromArray($request->validated()));

        return response()->json(['ok' => true]);
    }

    public function reassign(int $deliveryId, AssignDeliveryRequest $request, ReassignDeliveryPerson $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $uc->execute($accountId, $deliveryId, AssignDeliveryDTO::fromArray($request->validated()));

        return response()->json(['ok' => true]);
    }

    public function recordCollection(int $deliveryId, RecordCollectionRequest $request, RecordDeliveryCollection $uc): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');
        $uc->execute($accountId, $deliveryId, RecordCollectionDTO::fromArray($request->validated()));

        return response()->json(['ok' => true]);
    }
}
