<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\UploadImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
        use UploadImageTrait;

    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments', 'likes'])->get();
            return ApiResponse::sendResponse(200, 'Posts retrieved successfully', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    public function store(StorePostRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $this->uploadImage($request,'image','posts');

            $post = Post::create([
                'user_id' => auth()->id(),
                'content' => $validatedData['content'],
                'image' => $imagePath,
            ]);

            return ApiResponse::sendResponse(201, 'Post created successfully', new PostResource($post));
        } catch (Exception $e) {
                return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    public function show($id)
    {
        try {
            $posts = Post::with(['user', 'comments', 'likes'])->findOrFail($id);
            return ApiResponse::sendResponse(200, 'Post retrieved successfully', new PostResource($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        }
    }

    public function update(StorePostRequest $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $validatedData = $request->validated();

            $newImagePath = $this->uploadImage($request,'image','posts');

            if ($newImagePath && $post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->update([
                'content' => $validatedData['content'],
                'image' => $newImagePath ?? $post->image,
            ]);

            return ApiResponse::sendResponse(200, 'Post updated successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
        }
            $post->delete();
            return ApiResponse::sendResponse(200, 'Post deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    public function showPostLikes($postId)
    {
        try {
            $post = Post::findOrFail($postId);

            $likes = $post->likes()->with('user')->get();

            return ApiResponse::sendResponse(200, 'Likes retrieved successfully', LikeResource::collection($likes));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
