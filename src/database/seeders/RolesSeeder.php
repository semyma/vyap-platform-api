<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

final class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $platformAdmin = Role::findOrCreate('platform-admin', 'sanctum');
        $billingOwner  = Role::findOrCreate('billing-owner', 'sanctum');
        $rentalOwner   = Role::findOrCreate('rental-owner', 'sanctum');
        $platformUser = Role::findOrCreate('platform-user', 'sanctum');
        // Assign permissions
        // platform-admin gets everything
        $platformAdmin->syncPermissions(
            Permission::where('guard_name', 'sanctum')->get()
        );

        // billing-owner gets only billing.*
        $billingOwner->syncPermissions(
            Permission::where('name', 'like', 'billing.%')->get()
        );

        // rental-owner → later
    }
}
