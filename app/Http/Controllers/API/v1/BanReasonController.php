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
        $this->middleware('permission:ban_reason.show')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @apiResourceCollection App\Http\Resources\API\v1\BanReasonResource
     *
     * @apiResourceModel App\Models\BanReason
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $resources = BanReason::all();

        return BanReasonResource::collection($resources);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id string required The UUID of the ban reason.
     *
     * @apiResource App\Http\Resources\API\v1\BanReasonResource
     *
     * @apiResourceModel App\Models\BanReason
     */
    public function show(Request $request, $id): BanReasonResource|\Illuminate\Http\JsonResponse
    {
        $resource = BanReason::findOrFail($id);

        return new BanReasonResource($resource);
    }
}
