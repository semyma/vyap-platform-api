<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Middleware;

use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAccountRole
{
    public function __construct(
        private readonly TeamMemberRepository $teamRepo,
    ) {}

    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'ok' => false,
                'code' => 'UNAUTHENTICATED',
                'message' => 'Authentication required',
            ], 401);
        }

        $accountId = $request->attributes->get('active_account_id');

        if ($accountId === null) {
            return response()->json([
                'ok' => false,
                'code' => 'NO_ACTIVE_ACCOUNT',
                'message' => 'No active account selected.',
            ], 409);
        }

        $role = $this->teamRepo->getRoleForUserInAccount((int) $accountId, (int) $user->id);

        if ($role === null || strtolower($role) !== strtolower($requiredRole)) {
            return response()->json([
                'ok' => false,
                'code' => 'FORBIDDEN',
                'message' => 'You do not have permission for this action.',
            ], 403);
        }

        return $next($request);
    }
}
