<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountBanResource;
use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountBan;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Ban Game Jolt Account
 *
 * APIs for getting, creating, updating and deleting Game Jolt Account Bans.
 */
class GamejoltAccountBanController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }

    /**
     * Display a listing of the resources.
     *
     * @jsonresponse 200 {}
     */
    public function index(Request $request)
    {
        if (! $request->user()) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
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
        $resources = GamejoltAccountBan::with(['reason', 'gamejoltaccount'])->get();

        return GamejoltAccountBanResource::collection($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam gamejoltaccount_id int required The ID of the Game Jolt Account. Example: 123456
     * @bodyParam reason_id int required The ID of the Ban Reason. Example: 3
     * @bodyParam banned_by_id int optional The ID of the Game Jolt Account, default will be owner of token. Cannot be used with banned_by_gamejoltaccount_id. Example: 123456
     * @bodyParam banned_by_gamejoltaccount_id int optional The ID of the Game Jolt Account. Cannot be used with banned_by_id. Example: 123456
     * @bodyParam expires_at string optional The expiry of the ban. Example: 2020-01-01
     *
     * @jsonresponse 201 {
     *       "data": {
     *           "gamejoltaccount_id": 12345,
     *           "reason": {
     *              "name": "Abusing in-game glitches",
     *              "created_at": "2022-04-02T18:51:14.000000Z",
     *              "updated_at": "2022-04-02T18:51:14.000000Z"
     *            },
     *           "expires_at": "2021-02-01T00:00:00.000000Z",
     *           "banned_by": {
     *              "id": 1,
     *              "name": "John Doe",
     *              "email": "john.doe@example.com",
     *              "username": "john.doe",
     *              "email_verified_at": "2022-12-21T18:49:11.000000Z",
     *              "created_at": "2022-04-12T18:48:51.000000Z",
     *              "updated_at": "2023-06-11T16:45:56.000000Z",
     *              "profile_photo_url": ""
     *           },
     *           "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *           "updated_at": "2021-01-01T17:57:10.000000Z",
     *           "created_at": "2021-01-01T17:57:10.000000Z"
     *       }
     *   }
     */
    public function store(Request $request)
    {
        if (! $request->user()->tokenCan('create')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $request->validate([
            'gamejoltaccount_id' => 'required|integer',
            'reason_id' => 'required|integer',
            'banned_by_id' => 'nullable|integer',
            'banned_by_gamejoltaccount_id' => 'nullable|integer',
            'expire_at' => 'nullable|date',
        ]);
        $banned_by_id = $request->user()->id;
        if (isset($request->banned_by_id) && isset($request->banned_by_gamejoltaccount_id)) {
            return response()->json([
                'error' => 'banned_by_id and banned_by_gamejoltaccount_id cannot be used together!',
            ]);
        } elseif (! isset($request->banned_by_id) && isset($request->banned_by_gamejoltaccount_id)) {
            $gja = GamejoltAccount::where('id', $request->banned_by_gamejoltaccount_id)->first();
            if (! $gja) {
                return response()->json([
                    'error' => 'Game Jolt Account not found with banned_by_gamejoltaccount_id!',
                ]);
            }
            $banned_by_id = $gja->user->id;
        } elseif (isset($request->banned_by_id) && ! isset($request->banned_by_gamejoltaccount_id)) {
            $user = User::find($request->banned_by_id);
            if (! $user) {
                return response()->json([
                    'error' => 'User not found with banned_by_id!',
                ]);
            }
            $banned_by_id = $user->id;
        }
        $new_data = [
            'gamejoltaccount_id' => $request->gamejoltaccount_id,
            'reason_id' => $request->reason_id,
            'banned_by_id' => $banned_by_id,
            'expire_at' => $request->expire_at,
        ];
        $resource = GamejoltAccountBan::create($new_data);

        return new GamejoltAccountBanResource($resource);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id int required The ID of the Game Jolt Account.
     *
     * @jsonresponse 200 {
     *    "data": [
     *        {
     *            "id": 1,
     *            "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *            "gamejoltaccount_id": 12345,
     *            "banned_by_id": 1,
     *            "reason_id": 1,
     *            "expire_at": null,
     *            "created_at": "2021-01-01T17:57:10.000000Z",
     *            "updated_at": "2021-01-01T17:57:10.000000Z",
     *            "deleted_at": null
     *        },
     *    ]
     * }
     */
    public function show(Request $request, $id)
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
        $resources = GamejoltAccountBan::with(['reason', 'gamejoltaccount', 'banned_by'])
            ->where('gamejoltaccount_id', $id)
            ->get();

        return GamejoltAccountBanResource::collection($resources);
    }

    /**
     * Remove the specified resource.
     *
     * @urlParam id string required The UUID of the _ban_ you would like to remove
     *
     * @jsonresponse 202 {
     *  "success": 'Ban was removed!',
     * }
     */
    public function destroy(Request $request, $uuid)
    {
        if (! $request->user()->tokenCan('delete')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resource = GamejoltAccountBan::where('uuid', $uuid)->firstOrFail();
        $resource->delete();

        return response()
            ->json([
                'success' => 'Ban was removed!',
            ])
            ->setStatusCode(202);
    }
}
