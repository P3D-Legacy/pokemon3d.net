<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class SkinHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $skins = Auth::user()
            ->gamejolt->skins()
            ->get();

        return view('skin.index')
            ->with('skins', $skins);
    }
}
