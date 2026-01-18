<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Console;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

final class SeedRolesCommand extends Command
{
    protected $signature = 'vyap:auth:seed-roles';
    protected $description = 'Seed default platform roles for Vyap';

    public function handle(): int
    {
        $roles = [
            'platform-admin',
            'billing-owner',
            'rental-owner',
        ];

        foreach ($roles as $name) {
            Role::findOrCreate($name, 'sanctum');
            $this->info("Role ensured: {$name}");
        }

        return self::SUCCESS;
    }
}
