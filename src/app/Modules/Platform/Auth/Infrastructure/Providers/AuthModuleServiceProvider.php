<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Platform\Auth\Application\Contracts\OtpChallengeRepository;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Repositories\EloquentOtpChallengeRepository;
use App\Modules\Platform\Auth\Application\Contracts\UserRepository;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Repositories\EloquentUserRepository;


final class AuthModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
        OtpChallengeRepository::class,
        EloquentOtpChallengeRepository::class
    );

     $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->modulePath('Infrastructure/Persistence/Migrations'));

        $this->loadRoutesFrom($this->modulePath('Routes/api_v1.php'));

        if ($this->app->runningInConsole()) {
        $this->commands([
            \App\Modules\Platform\Auth\Console\SeedRolesCommand::class,
        ]);
    }
    }

    private function modulePath(string $path): string
    {
        return app_path('Modules/Platform/Auth/' . $path);
    }
}
