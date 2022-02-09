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
     * @response {
     *        "data": {
     *           "id": 1,
     *           "name": "Daniel S. Billing",
     *           "email": "daniel@rtrd.no",
     *           "username": "DanielRTRD",
     *           "about": null,
     *           "gender": 1,
     *           "location": null,
     *           "birthdate": null,
     *           "email_verified_at": "2021-12-21T20:59:59.000000Z",
     *           "current_team_id": null,
     *           "profile_photo_path": null,
     *           "created_at": "2021-12-21T20:58:39.000000Z",
     *           "updated_at": "2022-01-01T22:15:21.000000Z",
     *           "last_active_at": "2022-01-01T22:15:21.000000Z",
     *           "profile_photo_url": "https://ui-avatars.com/api/?name=Daniel+S.+Billing&color=7F9CF5&background=EBF4FF",
     *           "roles": [
     *                {
     *                    "id": 1,
     *                    "name": "super-admin",
     *                    "guard_name": "web",
     *                    "created_at": "2022-01-01T22:49:45.000000Z",
     *                    "updated_at": "2022-01-01T22:49:45.000000Z",
     *                    "pivot": {
     *                        "model_id": 1,
     *                        "role_id": 1,
     *                        "model_type": "App\\Models\\User"
     *                    },
     *                    "permissions": [
     *                        {
     *                            "id": 1,
     *                            "name": "manage.users",
     *                            "guard_name": "web",
     *                            "created_at": "2022-01-01T22:49:45.000000Z",
     *                            "updated_at": "2022-01-01T22:49:45.000000Z",
     *                            "pivot": {
     *                                "role_id": 1,
     *                                "permission_id": 1
     *                            }
     *                        }
     *                    ]
     *                }
     *            ],
     *        },
     *   }
     */
    public function show(Request $request, $id)
    {
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $user = User::with([
            'roles.permissions',
            'gamejolt',
            'forum',
        ])->findOrFail($id);

        return new UserResource($user);
    }
}
