<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|Factory|Application
    {
        return view('server.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Factory|Application
    {
        return view('server.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show(int $id)
    {
        //return redirect()->route('server.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server): View|Factory|Application
    {
        abort_if(! $server->user_id == auth()->user()->id, 403);

        return view('server.edit', compact('server'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy(int $id)
    {
        //
    }
}
