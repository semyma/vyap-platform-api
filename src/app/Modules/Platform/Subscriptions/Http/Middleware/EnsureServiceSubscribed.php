<?php

declare(strict_types=1);

namespace App\Modules\Platform\Subscriptions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Platform\Subscriptions\Application\Contracts\SubscriptionRepository;

final class EnsureServiceSubscribed
{
    public function __construct(
        private readonly SubscriptionRepository $subscriptions,
    ) {}

    public function handle(Request $request, Closure $next, string $serviceCode): Response
    {
        $user = $request->user();

        // Defensive: auth middleware should already handle this
        if ($user === null) {
            return response()->json([
                'ok' => false,
                'code' => 'UNAUTHENTICATED',
                'message' => 'Authentication required',
            ], 401);
        }

        $hasSubscription = $this->subscriptions->hasActiveSubscription(
            platformUserId: (int) $user->id,
            serviceCode: $serviceCode,
            nowEpoch: time(),
        );

        if (! $hasSubscription) {
            return response()->json([
                'ok' => false,
                'code' => 'SERVICE_NOT_SUBSCRIBED',
                'message' => "Service '{$serviceCode}' is not subscribed",
            ], 403);
        }

        return $next($request);
    }
}
