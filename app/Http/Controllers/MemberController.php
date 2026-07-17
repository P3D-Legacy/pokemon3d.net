<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\MemberPresenter;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('members/index', [
            'members' => User::verified()
                ->paginate(10)
                ->through(fn (User $user): array => MemberPresenter::card($user)),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $param): Response
    {
        $user = User::query()
            ->where('username', $param)
            ->orWhere('id', $param)
            ->with(['gamejolt.trophies', 'discord', 'twitch', 'facebook', 'gamesave'])
            ->firstOrFail();

        return Inertia::render('members/show', [
            'member' => MemberPresenter::show($user),
        ]);
    }
}
