<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:blog-create|blog-update|blog-destroy'])->only(['index']);
        $this->middleware(['permission:blog-create'])->only(['create', 'store']);
        $this->middleware(['permission:blog-update'])->only(['update', 'edit']);
        $this->middleware(['permission:blog-destroy'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('updated_at')->paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
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
            'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
            'active' => ['required', 'integer'],
            'body' => ['required', 'string', 'min:25'],
        ]);

        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->active = $request->active;
        $post->slug = Str::of($post->title)->slug('-');
        $post->user_id = auth()->user()->id;
        $post->save();

        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        return redirect()->route('blog.show', $param);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
            'active' => ['required', 'integer'],
            'body' => ['required', 'string', 'min:25'],
        ]);

        $post->title = $request->title;
        $post->body = $request->body;
        $post->active = $request->active;
        $post->slug = Str::of($post->title)->slug('-');
        $post->user_id = auth()->user()->id;
        $post->save();

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
