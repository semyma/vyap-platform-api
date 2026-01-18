<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Platform\Subscriptions\Application\Contracts\SubscriptionRepository;
use App\Modules\Platform\Subscriptions\Infrastructure\Persistence\Repositories\EloquentSubscriptionRepository;

final class SubscriptionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionRepository::class, EloquentSubscriptionRepository::class);
    }
}
