<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Delivery\Domain\Contracts\DeliveryOrderRepository;
use App\Modules\Delivery\Infrastructure\Persistence\MySql\MySqlDeliveryOrderRepository;

final class DeliveryModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DeliveryOrderRepository::class, MySqlDeliveryOrderRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('app/Modules/Delivery/Routes/api.php'));
    }
}
