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


    /**
     * @OA\Post(
     *     path="/posts/{postId}/likes",
     *     summary="Toggle like for a post",
     *     description="Toggle a like or unlike for a post by the authenticated user. Returns a message indicating whether the post was liked or unliked.",
     *     tags={"Likes"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post to like/unlike",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post liked successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Post liked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post unliked successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Post unliked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
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
