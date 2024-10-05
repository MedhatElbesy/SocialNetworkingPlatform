<?php

namespace App\Http\Controllers\API;

use App\Events\LikeToggled;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Repositories\like\LikeRepositoryInterface;
use Exception;
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
        try {
            $userId = Auth::id();
            $result = $this->likeRepository->toggleLike($postId, $userId);

            if ($result == 'liked') {
                broadcast(new LikeToggled($postId));
                return ApiResponse::sendResponse(201, 'Post liked successfully');
            } else {
                return ApiResponse::sendResponse(200, 'Post unliked successfully');
            }
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
