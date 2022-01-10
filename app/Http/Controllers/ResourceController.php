<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Rules\StrNotContain;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("resources.index", [
            "resources" => Resource::orderBy('name')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("resources.create");
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                new StrNotContain('official'),
            ],
            'breif' => [
                'required',
                'string',
            ],
            'description' => [
                'required',
                'string',
            ],
        ]);

        $resource = Resource::create([
            'name' => $request->name,
            'breif' => $request->breif,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('resource.show', ['resource' => $resource]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view("resources.show", [
            "resource" => $resource,
        ]);
    }
}
