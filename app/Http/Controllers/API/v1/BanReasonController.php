<?php

namespace App\Http\Controllers\API\v1;

use App\Models\BanReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\BanReasonResource;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $resources = BanReason::all();
        return new BanReasonResource($resources);
    }
}
