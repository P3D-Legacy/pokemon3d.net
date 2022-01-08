<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\GamejoltAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountResource;

/**
 * @group Gamejolt Account
 *
 * APIs for getting Gamejolt Accounts.
 */
class GamejoltAccountController extends Controller
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
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $gja = GamejoltAccount::with(['user.roles.permissions', 'bans', 'user.discord'])->where('id', $id)->firstOrFail();
        return new GamejoltAccountResource($gja);
    }
}
