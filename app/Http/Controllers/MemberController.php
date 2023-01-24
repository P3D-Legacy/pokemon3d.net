<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('member.index', ['users' => $users]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $param
     * @return View
     */
    public function show(string $param): View
    {
        $user = User::where('username', $param)
            ->orWhere('id', $param)
            ->firstOrFail();

        return view('member.show', compact('user'));
    }
}
