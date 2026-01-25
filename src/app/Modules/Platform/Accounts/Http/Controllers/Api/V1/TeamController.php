<?php

declare(strict_types=1);

namespace App\Modules\Platform\Accounts\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Platform\Accounts\Application\Team\InviteTeamMemberService;
use App\Modules\Platform\Accounts\Application\Team\ListTeamMembersService;
use App\Modules\Platform\Accounts\Application\Team\UpdateTeamMemberService;
use App\Modules\Platform\Accounts\Domain\Team\DTO\InviteTeamMemberDTO;
use App\Modules\Platform\Accounts\Http\Requests\Api\V1\InviteTeamMemberRequest;
use App\Modules\Platform\Accounts\Http\Requests\Api\V1\UpdateTeamMemberRequest;
use App\Modules\Platform\Accounts\Http\Resources\Api\V1\TeamMemberResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TeamController extends Controller
{
    public function index(Request $request, ListTeamMembersService $service): JsonResponse
    {
       $accountId = $this->activeAccountId($request);

        $members = $service->list($accountId);

        return response()->json([
            'ok' => true,
            'team' => TeamMemberResource::collection($members),
        ]);
    }

    public function invite(InviteTeamMemberRequest $request, InviteTeamMemberService $service): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

       $accountId = $this->activeAccountId($request);

        $dto = new InviteTeamMemberDTO(
            dialCode: (string) $request->string('dial_code'),
            phone: (string) $request->string('phone'),
            role: (string) $request->string('role'),
        );

        $accountUserId = $service->invite(
            accountId: $accountId,
            invitedByPlatformUserId: (int) $user->id,
            dto: $dto
        );

        return response()->json([
            'ok' => true,
            'account_user_id' => $accountUserId,
        ]);
    }

    public function update(int $accountUserId, UpdateTeamMemberRequest $request, UpdateTeamMemberService $service): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');

        $service->update(
            accountId: $accountId,
            accountUserId: $accountUserId,
            role: $request->input('role'),
            active: $request->has('active') ? (bool) $request->boolean('active') : null,
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(int $accountUserId, UpdateTeamMemberRequest $request, UpdateTeamMemberService $service): JsonResponse
    {
        $accountId = (int) $request->attributes->get('active_account_id');

        $service->remove($accountId, $accountUserId);

        return response()->json(['ok' => true]);
    }


    private function activeAccountId(Request $request): int
{
    $id = $request->attributes->get('active_account_id');

    if ($id === null) {
        abort(409, 'No active account selected.');
    }

    return (int) $id;
}

}
