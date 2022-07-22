<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\BanReasonResource;
use App\Models\BanReason;
use Illuminate\Http\Request;

/**
 * @group Ban Reason
 *
 * APIs for getting ban reasons.
 */
class BanReasonController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @response {
     *    "data": [
     *        {
     *            "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *            "name": "Abusing in-game glitches",
     *            "user_id": 1,
     *            "created_at": "2021-01-01T17:57:10.000000Z",
     *            "updated_at": "2021-01-01T17:57:10.000000Z",
     *            "deleted_at": null
     *        },
     *        {
     *            "id": 2,
     *            "uuid": "ba3900a8-bc82-43c6-9d4f-1760205f95f6",
     *            "name": "General hacking or cheating",
     *            "user_id": 1,
     *            "created_at": "2022-01-04T18:49:46.000000Z",
     *            "updated_at": "2022-01-04T18:49:46.000000Z",
     *            "deleted_at": null
     *        },
     *    ]
     * }
     */
    public function index(Request $request)
    {
        if (! $request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resources = BanReason::all();

        return BanReasonResource::collection($resources);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id string required The UUID of the ban reason.
     *
     * @response {
     *    "data": [
     *        {
     *            "uuid": "1830ef92-b58b-4671-9096-2b7741c0b0d8",
     *            "name": "Abusing in-game glitches",
     *            "user_id": 1,
     *            "created_at": "2021-01-01T17:57:10.000000Z",
     *            "updated_at": "2021-01-01T17:57:10.000000Z",
     *            "deleted_at": null
     *        },
     *    ]
     * }
     */
    public function show(Request $request, $id)
    {
        if (! $request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resource = BanReason::findOrFail($id);

        return new BanReasonResource($resource);
    }
}
