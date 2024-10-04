<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost($postId)
    {
        try {
            $user = auth()->user();
            $post = Post::findOrFail($postId);

            $existingLike = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

            if ($existingLike) {
                return ApiResponse::sendResponse(409, 'You have already liked this post.');
            }

            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);

            return ApiResponse::sendResponse(201, 'Post liked successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    public function unlikePost($postId)
    {
        try {
            $user = auth()->user();
            $post = Post::findOrFail($postId);

            $like = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

            if (!$like) {
                return ApiResponse::sendResponse(404, 'You haven\'t liked this post.');
            }

            $like->delete();

            return ApiResponse::sendResponse(200, 'Post unliked successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

}
