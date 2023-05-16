<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadedSkinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:skin-player-destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skins = Skin::all();

        return view('skin.uploaded.index')->with('skins', $skins);
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
    public function destroy(Request $request, $uuid)
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);
        $skin = Skin::where('uuid', $uuid)->first();
        if (! Storage::disk('skin')->exists($skin->path())) {
            return redirect()
                ->route('uploaded-skins')
                ->with('error', 'Skin was not found!');
        }
        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => $skin->path(),
                'gjid' => $skin->user->id,
                'reason' => $request->reason,
            ])
            ->log('deleted');
        $skin->delete();
        Storage::disk('skin')->delete($skin->path());

        return redirect()
            ->route('uploaded-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}
