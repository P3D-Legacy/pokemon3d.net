<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        $auth = $api->users()->fetch($request->session()->get('gju'), $request->session()->get('gjt'));
        $user = null;
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            return view('home')->with('error', $auth['response']['message']);
        }
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === true) {
            $user = $auth['response']['users'][0];
        }

        $activity = Activity::where('description' , 'deleted')->where('properties', 'LIKE', '%'.$request->session()->get('gjid').'.png%')->orWhere('properties', 'LIKE', '%gjid":'.$request->session()->get('gjid').',"reason"%')->get();

        return view('home')->with($user)->with('activity', $activity);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
