<?php

namespace App\Http\Controllers\Save;

use App\Http\Controllers\Controller;
use App\Support\GameSavePresenter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MySaveController extends Controller
{
    /**
     * Display the authenticated user's game save.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('save/index', [
            'gameSave' => GameSavePresenter::forOwner($request->user()),
        ]);
    }
}
