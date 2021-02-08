<?php

namespace App\Http\Controllers;

use App\Models\GJUser;
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
        $username = $request->username;
        $token = $request->token;

        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        $auth = $api->users()->auth($username, $token);
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            return redirect()->route('login')->with('warning', $auth['response']['message'])->withInput();
        }
        $user = $api->users()->fetch($username, $token);
        $id = $user['response']['users'][0]['id'];
        $avatar_url = $user['response']['users'][0]['avatar_url'];

        // Remember the user in the session
        $request->session()->put('gju', $username);
        $request->session()->put('gjt', $token);
        $request->session()->put('gjid', $id);
        $request->session()->put('gjau', $avatar_url); // Avatar url

        // Let's store the user id and username in the database, we will not store the token!
        $gjuser = GJUser::where('gjid', $id)->where('gju', $username)->first();
        // If user is not already stored, create it
        if(!$gjuser) {
            GJUser::create([
                'gjid' => $id,
                'gju' => $username,
            ]);
        }

        // Open a session for the given user
        $api->sessions()->open($username, $token);
        return redirect()->route('home')->with('success', 'You successfully logged in with Gamejolt!');
    }

    public function logout(Request $request)
    {
        
        $username = $request->session()->get('gju');
        $token = $request->session()->get('gjt');
        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        //Close the session for user
        $api->sessions()->close($username, $token);
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'You successfully logged out!');
    }
}
