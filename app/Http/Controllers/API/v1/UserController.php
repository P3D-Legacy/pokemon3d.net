<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\UserResource;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::with(['roles.permissions', 'gamejolt', 'forum'])->findOrFail($id);
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        if ($request->user()->id !== $user->id) {
            return response()->json([
                'error' => 'You are not allowed to view this user!',
            ]);
        }
        return new UserResource($user);
    }

}
