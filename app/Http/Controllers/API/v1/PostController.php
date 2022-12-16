<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @group Post
 *
 * APIs for posts.
 */
class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam title string required The title of the post. Example: Hello World
     * @bodyParam body string required The content of the post. Example: This is a post.
     * @bodyParam active boolean required Whether the post is active or not. Example: true
     * @bodyParam sticky boolean required Whether the post is sticky or not. Example: false
     * @bodyParam user_id int required The ID of the user. Example: 1
     * @bodyParam published_at string optional The date the post was published. Example: 2021-01-01
     *
     * @response 201 {
     *      "title": "Test",
     *      "body": "Test",
     *      "active": True,
     *      "sticky": False,
     *      "user_id": 1,
     *      "published_at": "2021-12-21T20:59:14.000000Z",
     *      "created_at": "2021-12-21T20:59:14.000000Z",
     *      "updated_at": "2021-12-21T20:59:14.000000Z",
     *      "deleted_at": null,
     * }
     **/
    public function store(Request $request)
    {
        if (! $request->user()->tokenCan('create')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'active' => 'required|boolean',
            'sticky' => 'required|boolean',
            'published_at' => 'required|date',
            'user_id' => 'required|integer',
        ]);
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

}
