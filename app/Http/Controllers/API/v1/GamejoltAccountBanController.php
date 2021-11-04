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
