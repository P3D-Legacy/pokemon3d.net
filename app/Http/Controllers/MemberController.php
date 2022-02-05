<?php

namespace App\Http\Controllers;

use App\Models\User;

class MemberController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view("member.show", compact("user"));
    }
}
