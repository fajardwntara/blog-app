<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function listPost()
    {
        $data = Post::with(['category', 'user'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data found',
            'data' => $data,
        ], 200);
    }

    public function getPost($id)
    {
        $post = Post::with(['category', 'user'])->find($id);

        if ($post) {
            return response()->json([
                'status' => true,
                'message' => "Data found",
                'data' => $post->load('user'),
                'audit' => $post->audits,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Data not found",
                'data' => $post
            ], 404);
        }
    }

    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|uuid|exists:categories,id',
            'content' => 'required|string',
            'publication_date' => 'nullable|date',
        ]);

        try {
            $post = Post::create([
                'title' => $validated['title'],
                'category_id' => $validated['category_id'],
                'content' => $validated['content'],
                'created_by' => Auth::id(),
                'publication_date' => $validated['publication_date'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully created a new post',
                'data' => $post->load(['category', 'user'])
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create post',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function updatePost(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            if (!$post) {
                return response()->json([
                    'status' => false,
                    'message' => 'Post not found',
                ], 404);
            }

            $request->validate(
                [
                    'title' => 'required|string|max:255',
                    'category_id' => 'required|uuid|exists:categories,id',
                    'content' => 'required|string',
                    'publication_date' => 'nullable|date',
                ]
            );

            $post->title = $request->title;
            $post->category_id = $request->category_id;
            $post->content = $request->content;
            $post->publication_date = $request->publication_date;

            $post->save();

            return response()->json([
                'status' => true,
                'message' => '(' . $request->title . ') has been updated',
                'data' => $post->load(['category', 'user'])
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete post',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    public function deletePost($id)
    {
        try {
            $post = Post::findOrFail($id);

            $post->delete();

            return response()->json([
                'status' => true,
                'message' => '(' . $post->title . ') has been deleted',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
