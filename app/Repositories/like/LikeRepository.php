<?php

namespace App\Repositories\like;

use App\Models\Like;
use App\Models\Post;
// use App\Repositories\like\LikeRepositoryInterface;
use App\Repositories\like\LikeRepositoryInterface;
use Exception;

class LikeRepository implements LikeRepositoryInterface
{
    public function toggleLike($postId, $userId)
    {
        try {
            $post = Post::findOrFail($postId);

            $like = Like::where('post_id', $postId)->where('user_id', $userId)->first();

            if ($like) {
                $like->delete();
                return 'unliked';
            } else {
                Like::create([
                    'post_id' => $postId,
                    'user_id' => $userId,
                ]);
                return 'liked';
            }
        } catch (Exception $e) {
            throw new Exception('An error occurred while toggling like: ' . $e->getMessage());
        }
    }
}
