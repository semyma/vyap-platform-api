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

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'code' => 'UNAUTHENTICATED',
                'message' => 'Authentication required',
            ], 401);
        }

        // ✅ Active account must be resolved BEFORE this middleware (by active.account)
        $accountId = $request->attributes->get('active_account_id');

        if ($accountId === null) {
            return response()->json([
                'ok' => false,
                'code' => 'NO_ACTIVE_ACCOUNT',
                'message' => 'No active account selected. Switch/select an account.',
            ], 409);
        }

        $hasSubscription = $this->subscriptions->hasActiveSubscriptionForAccount(
            accountId: (int) $accountId,
            serviceCode: $serviceCode,
            nowEpoch: time(),
        );

        if (! $hasSubscription) {
            return response()->json([
                'ok' => false,
                'code' => 'SERVICE_NOT_SUBSCRIBED',
                'message' => "Service '{$serviceCode}' is not subscribed for this account",
            ], 403);
        }

        return $next($request);
    }
}
