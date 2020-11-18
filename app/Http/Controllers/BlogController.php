<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Blog::all();
    }


    public function show($id)
    {
        $post = Blog::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ],404);
        }

        return $post;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'isi' => 'required',
            'penulis' => 'required'
        ]);

        $blog = new Blog($request->all());

        $blog->user_id = $request->auth->id;

        if ($blog->save())
            return response()->json([
                'message' => 'Sukses yay!',
                'data' => $blog
            ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = Blog::find($id);

        if ($post) {
            if ($post->user_id != $request->auth->id)
                return response()->json([
                    'message' => 'Anda tidak berhak!!!'
                ], 401);

            $post->update($request->all());
            return response()->json([
                'message' => 'Post has been updated',
            ]);
        }

        return response()->json([
            'message' => 'Post not found'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        $post = Blog::find($id);

        if ($post) {
            if ($post->user_id != $request->auth->id)
                return response()->json([
                    'message' => 'Anda tidak berhak!!!'
                ], 401);

            $post->delete();

            return response()->json([
                'message' => 'Post has been deleted'
            ]);
        }

        return response()->json([
            'message' => 'Post not found'
        ], 404);
    }
}
