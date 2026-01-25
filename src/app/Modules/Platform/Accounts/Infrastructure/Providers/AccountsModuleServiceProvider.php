<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Platform\Accounts\Domain\Repositories\AccountMembershipRepository;
use App\Modules\Platform\Accounts\Domain\Repositories\ActiveAccountRepository;
use App\Modules\Platform\Accounts\Infrastructure\Repositories\DbAccountMembershipRepository;
use App\Modules\Platform\Accounts\Infrastructure\Repositories\DbActiveAccountRepository;
use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;
use App\Modules\Platform\Accounts\Infrastructure\Team\Repositories\DbTeamMemberRepository;


final class AccountsModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AccountMembershipRepository::class, DbAccountMembershipRepository::class);
        $this->app->bind(ActiveAccountRepository::class, DbActiveAccountRepository::class);
        $this->app->bind(TeamMemberRepository::class, DbTeamMemberRepository::class);
    }

    public function boot(): void
    {
        // If you later add module-specific migrations (optional)
        // $this->loadMigrationsFrom($this->modulePath('Infrastructure/Persistence/Migrations'));

        $this->loadRoutesFrom($this->modulePath('Routes/api_v1.php'));
    }

    private function modulePath(string $path): string
    {
        return app_path('Modules/Platform/Accounts/' . $path);
    }
}
