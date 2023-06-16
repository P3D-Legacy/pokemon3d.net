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
     * @response {
     *    "data": [
     *        {
     *            "id": 1,
     *            "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *            "id": 1234567890,
     *            "username": "DanielRTRD",
     *            "discriminator": 9659,
     *            "verified_at": "2021-01-01T17:57:10.000000Z",
     *            "created_at": "2021-01-01T17:57:10.000000Z",
     *            "updated_at": "2021-01-01T17:57:10.000000Z",
     *            "deleted_at": null
     *        },
     *    ]
     * }
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|DiscordAccountResource
    {
        if (! $request->user()) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        if (! $request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $account = DiscordAccount::with(['roles', 'user.roles.permissions'])->findOrFail($id);

        return new DiscordAccountResource($account);
    }
}
