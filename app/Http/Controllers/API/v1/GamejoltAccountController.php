<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountResource;
use App\Models\GamejoltAccount;
use Illuminate\Http\Request;

/**
 * @group Game Jolt Account
 *
 * APIs for getting Game Jolt Accounts.
 */
class GamejoltAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gamejolt_account.show')->only(['show']);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id int required The ID of the Game Jolt Account.
     *
     * @apiResource App\Http\Resources\API\v1\GamejoltAccountResource
     *
     * @apiResourceModel App\Models\GamejoltAccount
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|GamejoltAccountResource
    {
        $gja = GamejoltAccount::with(['user.roles.permissions', 'bans', 'user.discord'])
            ->where('id', $id)
            ->firstOrFail();

        return new GamejoltAccountResource($gja);
    }
}
