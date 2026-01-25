<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class DeliveryOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $d = $this->resource;

        return [
            'id' => $d->id,
            'source_bill_id' => $d->sourceBillId,
            'customer_name' => $d->customerName,
            'customer_phone' => $d->customerPhone,
            'address_line' => $d->addressLine,
            'pincode' => $d->pincode,
            'status' => $d->status,
            'assigned_account_user_id' => $d->assignedAccountUserId,
            'amount_to_collect' => $d->amountToCollect,
            'collected_amount' => $d->collectedAmount,
            'collected_via' => $d->collectedVia,
            'collection_ref' => $d->collectionRef,
            'collected_at' => $d->collectedAt,
            'created_at' => $d->createdAt,
            'updated_at' => $d->updatedAt,
        ];
    }
}
