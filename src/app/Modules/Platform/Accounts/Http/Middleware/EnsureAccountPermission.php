<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Middleware;

use App\Modules\Platform\Accounts\Domain\Team\Repositories\TeamMemberRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAccountPermission
{
    public function __construct(
        private readonly TeamMemberRepository $teamRepo,
    ) {}

    public function handle(Request $request, Closure $next, string $permission): Response
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

        if ($role === null) {
            return response()->json([
                'ok' => false,
                'code' => 'FORBIDDEN',
                'message' => 'No account membership found.',
            ], 403);
        }

        $allowed = $this->isAllowed((string) $role, $permission);

        if (! $allowed) {
            return response()->json([
                'ok' => false,
                'code' => 'FORBIDDEN',
                'message' => 'You do not have permission for this action.',
                'required' => $permission,
            ], 403);
        }

        return $next($request);
    }

    private function isAllowed(string $role, string $permission): bool
    {
        $role = strtolower($role);

        /** @var array{roles: array<string, array<int, string>>} $cfg */
        $cfg = config('account_permissions');

        $perms = $cfg['roles'][$role] ?? [];

        if (in_array('*', $perms, true)) {
            return true;
        }

        return in_array($permission, $perms, true);
    }
}
