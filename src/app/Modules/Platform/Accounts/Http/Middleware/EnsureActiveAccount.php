<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Middleware;

use App\Modules\Platform\Accounts\Application\ActiveAccountResolver;
use Closure;
use Illuminate\Http\Request;

final class EnsureActiveAccount
{
    public function __construct(
        private readonly ActiveAccountResolver $resolver,
    ) {}

    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $activeAccountId = $this->resolver->resolveActiveAccountId((int) $user->id);

        // Attach to request for downstream middleware/controllers/services
        $request->attributes->set('active_account_id', $activeAccountId);

        return $next($request);
    }
}
