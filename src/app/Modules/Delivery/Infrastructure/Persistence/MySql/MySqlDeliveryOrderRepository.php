<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Infrastructure\Persistence\MySql;

use Illuminate\Support\Facades\DB;
use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;
use App\Modules\Delivery\Application\DTOs\CreateDeliveryOrderDTO;
use App\Modules\Delivery\Application\DTOs\AssignDeliveryDTO;
use App\Modules\Delivery\Application\DTOs\UpdateDeliveryStatusDTO;
use App\Modules\Delivery\Application\DTOs\RecordCollectionDTO;
use App\Modules\Delivery\Domain\Entities\DeliveryOrder;

final class MySqlDeliveryOrderRepository implements DeliveryOrderRepository
{
    private const TABLE = 'delivery_orders';

    public function create(int $accountId, int $createdByAccountUserId, CreateDeliveryOrderDTO $dto): int
    {
        $id = DB::table(self::TABLE)->insertGetId([
            'account_id' => $accountId,
            'source_bill_id' => $dto->sourceBillId,
            'customer_name' => $dto->customerName,
            'customer_phone' => $dto->customerPhone,
            'address_line' => $dto->addressLine,
            'pincode' => $dto->pincode,
            'status' => 'pending',
            'assigned_account_user_id' => null,
            'amount_to_collect' => $dto->amountToCollect,
            'collected_amount' => 0,
            'collected_via' => null,
            'collection_ref' => null,
            'collected_at' => null,
            'created_by_account_user_id' => $createdByAccountUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (int)$id;
    }

    public function findForAccount(int $accountId, int $deliveryId): ?DeliveryOrder
    {
        $row = DB::table(self::TABLE)
            ->where('account_id', $accountId)
            ->where('id', $deliveryId)
            ->first();

        return $row ? $this->mapRow($row) : null;
    }

    public function listForAccount(int $accountId, array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $q = DB::table(self::TABLE)->where('account_id', $accountId);

        if (!empty($filters['status'])) {
            $q->where('status', (string)$filters['status']);
        }

        $rows = $q->orderByDesc('id')->limit($limit)->offset($offset)->get();

        return array_map(fn($r) => $this->mapRow($r), $rows->all());
    }

    public function listAssignedToMe(int $accountId, int $accountUserId, array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $q = DB::table(self::TABLE)
            ->where('account_id', $accountId)
            ->where('assigned_account_user_id', $accountUserId);

        if (!empty($filters['status'])) {
            $q->where('status', (string)$filters['status']);
        }

        $rows = $q->orderByDesc('id')->limit($limit)->offset($offset)->get();

        return array_map(fn($r) => $this->mapRow($r), $rows->all());
    }

    public function assign(int $accountId, int $deliveryId, AssignDeliveryDTO $dto): void
    {
        DB::table(self::TABLE)
            ->where('account_id', $accountId)
            ->where('id', $deliveryId)
            ->update([
                'assigned_account_user_id' => $dto->assignedAccountUserId,
                'status' => 'assigned',
                'updated_at' => now(),
            ]);
    }

    public function updateStatus(int $accountId, int $deliveryId, UpdateDeliveryStatusDTO $dto): void
    {
        DB::table(self::TABLE)
            ->where('account_id', $accountId)
            ->where('id', $deliveryId)
            ->update([
                'status' => $dto->status,
                'failure_reason' => $dto->failureReason,
                'updated_at' => now(),
            ]);
    }

    public function recordCollection(int $accountId, int $deliveryId, RecordCollectionDTO $dto): void
    {
        DB::table(self::TABLE)
            ->where('account_id', $accountId)
            ->where('id', $deliveryId)
            ->update([
                'collected_amount' => $dto->collectedAmount,
                'collected_via' => $dto->collectedVia,
                'collection_ref' => $dto->collectionRef,
                'collected_at' => $dto->collectedAt ? $dto->collectedAt : now(),
                'updated_at' => now(),
            ]);
    }

    private function mapRow(object $r): DeliveryOrder
    {
        return new DeliveryOrder(
            id: (int)$r->id,
            accountId: (int)$r->account_id,
            sourceBillId: $r->source_bill_id !== null ? (int)$r->source_bill_id : null,
            customerName: (string)$r->customer_name,
            customerPhone: (string)$r->customer_phone,
            addressLine: (string)$r->address_line,
            pincode: $r->pincode !== null ? (string)$r->pincode : null,
            status: (string)$r->status,
            assignedAccountUserId: $r->assigned_account_user_id !== null ? (int)$r->assigned_account_user_id : null,
            amountToCollect: (float)$r->amount_to_collect,
            collectedAmount: (float)$r->collected_amount,
            collectedVia: $r->collected_via !== null ? (string)$r->collected_via : null,
            collectionRef: $r->collection_ref !== null ? (string)$r->collection_ref : null,
            collectedAt: $r->collected_at !== null ? (string)$r->collected_at : null,
            createdAt: (string)$r->created_at,
            updatedAt: (string)$r->updated_at,
        );
    }
}
