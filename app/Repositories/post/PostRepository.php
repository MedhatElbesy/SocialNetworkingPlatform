<?php

namespace App\Repositories\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPosts()
    {
        return Post::with(['user', 'comments', 'likes'])->get();
    }

    public function findPostById($id)
    {
        return Post::with(['user', 'comments', 'likes'])->findOrFail($id);
    }

    public function createPost(array $data)
    {
        return Post::create($data);
    }

    public function updatePost($post, array $data)
    {
        $newImagePath = $data['image'] ?? null;

        if ($newImagePath && $post->image) {
            Storage::disk('public')->delete($post->image);
        }

        return $post->update([
            'content' => $data['content'],
            'image' => $newImagePath ?? $post->image,
        ]);
    }

    public function deletePost($post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        return $post->delete();
    }

    public function getPostLikes($postId)
    {
        $post = Post::findOrFail($postId);
        return $post->likes()->with('user')->get();
    }
}
