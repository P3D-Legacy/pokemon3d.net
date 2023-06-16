<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group User
 *
 * APIs for getting Users.
 */
class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id int required The ID of the user.
     *
     * @apiResource App\Http\Resources\API\v1\UserResource
     * @apiResourceModel App\Models\User
     *
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|UserResource
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
        $user = User::with(['roles.permissions', 'gamejolt', 'forum'])->findOrFail($id);

        return new UserResource($user);
    }
}
