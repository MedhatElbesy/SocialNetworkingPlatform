<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($postId)
    {
        $post = Post::findOrFail($postId);
        $userId = Auth::id();

        $like = Like::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            return redirect()->back()->with('success', 'Post unliked successfully!');
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => $userId,
            ]);
            return redirect()->back()->with('success', 'Post liked successfully!');
        }
    }
}
