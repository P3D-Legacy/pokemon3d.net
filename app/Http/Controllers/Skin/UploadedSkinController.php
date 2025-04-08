<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UploadedSkinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            ['permission:skin-player-destroy'],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
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
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Request $request, $uuid): RedirectResponse
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
