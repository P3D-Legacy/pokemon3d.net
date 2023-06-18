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
     *
     * @apiResourceModel App\Models\User
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse|UserResource
    {
        $user = User::with(['roles.permissions', 'gamejolt', 'forum'])->findOrFail($id);

        return new UserResource($user);
    }
}
