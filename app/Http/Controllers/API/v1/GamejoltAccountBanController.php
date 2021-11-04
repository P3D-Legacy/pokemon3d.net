<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\GamejoltAccountBan;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountBanResource;

/**
 * @group Ban Gamejolt Account
 *
 * APIs for getting, creating, updating and deleting Gamejolt Account Bans.
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
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
     * @bodyParam gamejolt_account_id int required The ID of the Gamejolt Account.
     * @bodyParam reason_id int required The ID of the Ban Reason.
     * @bodyParam expires_at date optional The expiry of the ban.
     * 
     * @response 201 {
     *       "data": {
     *           "gamejoltaccount_id": 12345,
     *           "reason_id": 1,
     *           "expires_at": "2021-02-01T00:00:00.000000Z",
     *           "banned_by_id": 1,
     *           "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *           "updated_at": "2021-01-01T17:57:10.000000Z",
     *           "created_at": "2021-01-01T17:57:10.000000Z"
     *       }
     *   }
     */
    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('create')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $request->validate([
            'gamejoltaccount_id' => 'required|integer',
            'reason_id' => 'required|integer',
            'expire_at' => 'nullable|date',
        ]);
        $data = $request->all();
        $data = array_merge($data, [
            'banned_by_id' => $request->user()->id,
        ]);
        $resource = GamejoltAccountBan::create($data);
        return new GamejoltAccountBanResource($resource);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id int required The ID of the Gamejolt Account.
     */
    public function show(Request $request, $id)
    {
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resource = GamejoltAccountBan::where('gamejoltaccount_id', $id)->get();
        abort_unless($resource, 404);
        return new GamejoltAccountBanResource($resource);
    }

    /**
     * Remove the specified resource.
     *
     * @urlParam id string required The UUID of the ban.
     * @response 202 {
     *  "success": 'Ban was removed!',
     * }
    */
    public function destroy(Request $request, $uuid)
    {
        if (!$request->user()->tokenCan('delete')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resource = GamejoltAccountBan::where('uuid', $uuid)->findOrFail();
        $resource->delete();
        return response()->json([
            'success' => 'Ban was removed!',
        ])->setStatusCode(202);
    }
}
