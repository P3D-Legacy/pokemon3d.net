<?php

namespace App\Http\Controllers\Save;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MySaveController extends Controller
{
    /**
     * Display the game save stub page.
     */
    public function index(): Response
    {
        return Inertia::render('save/index');
    }
}
