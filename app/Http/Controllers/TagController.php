<?php

namespace App\Http\Controllers;

use Spatie\Tags\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'permission:tags.create|tags.update|tags.destroy',
        ])->only(['index']);
        $this->middleware(['permission:tags.create'])->only([
            'create',
            'store',
        ]);
        $this->middleware(['permission:tags.update'])->only(['update', 'edit']);
        $this->middleware(['permission:tags.destroy'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'desc')->paginate(10);
        return view('tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['string', 'required'],
        ]);
        Tag::create($validatedData);
        session()->flash('flash.banner', 'Created Tag!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return view('tag.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $request->validate([
            'name' => ['string', 'required'],
        ]);
        $tag->update($validatedData);
        session()->flash('flash.banner', 'Updated Tag!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        session()->flash('flash.banner', 'Deleted Tag!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('tags.index');
    }
}
