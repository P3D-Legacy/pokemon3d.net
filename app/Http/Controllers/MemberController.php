<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $param
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
