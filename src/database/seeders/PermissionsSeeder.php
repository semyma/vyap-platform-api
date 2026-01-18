<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Modules\Billing\Auth\Permissions\BillingPermissions;

final class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = array_merge(
            BillingPermissions::all(),
            // Later: RentalPermissions::all(),
            // Later: DeliveryPermissions::all(),
        );

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'sanctum');
        }
    }
}
