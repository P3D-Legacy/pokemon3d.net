<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\DiscordAccountResource;
use App\Models\DiscordAccount;
use Illuminate\Http\Request;

/**
 * @group Discord Account
 *
 * APIs for getting Discord accounts.
 */
class DiscordAccountController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @urlParam id int required The ID of the Discord Account.
     *
     * @apiResource App\Http\Resources\API\v1\DiscordAccountResource
     *
     * @apiResourceModel App\Models\DiscordAccount
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|DiscordAccountResource
    {
        $account = DiscordAccount::with(['roles', 'user.roles.permissions'])->findOrFail($id);

        return new DiscordAccountResource($account);
    }
}
