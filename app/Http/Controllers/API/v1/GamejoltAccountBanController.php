<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\GamejoltAccountBan;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountBanResource;

class GamejoltAccountBanController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }

    /**
     * Display a listing of the resource.
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
}
