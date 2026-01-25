<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Platform\Accounts\Application\AccountSwitchService;
use App\Modules\Platform\Accounts\Domain\DTO\AccountSwitchDTO;
use App\Modules\Platform\Accounts\Domain\Repositories\AccountMembershipRepository;
use App\Modules\Platform\Accounts\Http\Requests\Api\V1\SwitchAccountRequest;
use App\Modules\Platform\Accounts\Http\Resources\Api\V1\AccountListResource;
use App\Modules\Platform\Accounts\Http\Resources\Api\V1\ActiveAccountResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


final class AccountController extends Controller
{
    public function index(
    Request $request,
    AccountMembershipRepository $membershipRepo
): JsonResponse {
    /** @var \App\Models\User $user */
    $user = $request->user();

    $accounts = $membershipRepo->listAccountsForUser((int) $user->id);

    return response()->json([
        'ok' => true,
        'accounts' => AccountListResource::collection($accounts),
    ]);
}

    public function switch(
    SwitchAccountRequest $request,
    AccountSwitchService $service,
    AccountMembershipRepository $membershipRepo,
): JsonResponse {
    /** @var \App\Models\User $user */
    $user = $request->user();

        $accountId = (int) $request->integer('account_id');
        $dto = new AccountSwitchDTO($accountId);

        DB::transaction(function () use ($service, $user, $dto): void {
            $service->switch((int) $user->id, $dto);
        });

        // return the new active account snapshot (id, name, role)
        $account = DB::table('accounts')->where('id', $accountId)->first(['id', 'name']);

        $role = $membershipRepo->getUserRoleInAccount((int) $user->id, $accountId) ?? 'staff';

        return response()->json([
            'ok' => true,
            'active_account' => new ActiveAccountResource([
                'id' => (int) $account->id,
                'name' => (string) $account->name,
                'role' => (string) $role,
            ]),
        ]);
    }
}
