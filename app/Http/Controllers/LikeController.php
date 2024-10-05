<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Repositories\like\LikeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    protected $likeRepository;

    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }
    public function toggle($postId)
    {
            $userId = Auth::id();
            $result = $this->likeRepository->toggleLike($postId, $userId);

        if ($result == 'liked') {
            return redirect()->back()->with('success', 'Post liked  successfully!');
        } else {
            return redirect()->back()->with('success', 'Post unliked successfully!');
        }
    }
}
