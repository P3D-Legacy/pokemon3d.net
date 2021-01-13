<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.guest'])->except('logout');
        $this->middleware(['gj.auth'])->except(['login', 'index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        if (!env("GAMEJOLT_GAME_ID") || !env("GAMEJOLT_GAME_PRIVATE_KEY")) {
            redirect()->route('login')->with('error', 'Gamejolt API keys is not set by an admin!');
        }
        $request->validate([
            'username' => ['required', 'string'],
            'token' => ['required', 'string']
        ]);

        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        $auth = $api->users()->auth($request->username, $request->token);
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            return redirect()->route('login')->with('warning', $auth['response']['message'])->withInput();
        }
        $request->session()->put('gjid', $request->username);
        return redirect()->route('home')->with('success', 'You successfully logged in with Gamejolt!');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'You successfully logged out!');
    }
}
