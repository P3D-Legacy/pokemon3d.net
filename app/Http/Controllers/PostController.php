<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:posts.create|posts.update|posts.destroy'])->only(['index']);
        $this->middleware(['permission:posts.create'])->only(['create', 'store']);
        $this->middleware(['permission:posts.update'])->only(['update', 'edit']);
        $this->middleware(['permission:posts.destroy'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->paginate(10);

        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('posts.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        return redirect()->route('posts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('posts.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('posts.index');
    }
}
