<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group User
 *
 * APIs for getting Users.
 */
class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user.show', only: ['show']),
        ];
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
