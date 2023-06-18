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
     * @apiResourceCollection App\Http\Resources\API\v1\GamejoltAccountBanResource
     *
     * @apiResourceModel App\Models\GamejoltAccountBan
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
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
     * @apiResource App\Http\Resources\API\v1\GamejoltAccountBanResource
     *
     * @apiResourceModel App\Models\GamejoltAccountBan
     */
    public function store(Request $request): GamejoltAccountBanResource|\Illuminate\Http\JsonResponse
    {
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
     * @apiResourceCollection App\Http\Resources\API\v1\GamejoltAccountBanResource
     *
     * @apiResourceModel App\Models\GamejoltAccountBan
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
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
     * @apiResource App\Http\Resources\API\v1\GamejoltAccountBanResource
     *
     * @apiResourceModel App\Models\GamejoltAccountBan
     */
    public function destroy(Request $request, $uuid): \Illuminate\Http\JsonResponse
    {
        $resource = GamejoltAccountBan::where('uuid', $uuid)->firstOrFail();
        $resource->delete();

        return response()
            ->json([
                'success' => 'Ban was removed!',
            ])
            ->setStatusCode(202);
    }
}
