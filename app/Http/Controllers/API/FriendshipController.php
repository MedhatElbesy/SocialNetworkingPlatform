<?php

namespace App\Http\Controllers\API;

use App\Events\FriendRequestSent;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use App\Repositories\Friendship\FriendshipRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    protected $friendshipRepository;

    public function __construct(FriendshipRepositoryInterface $friendshipRepository)
    {
        $this->friendshipRepository = $friendshipRepository;
    }


    /**
     * @OA\Post(
     *     path="/friend-request/send/{friendId}",
     *     summary="Send a friend request",
     *     description="Send a friend request to another user.",
     *     tags={"Friendships"},
     *     @OA\Parameter(
     *         name="friendId",
     *         in="path",
     *         description="ID of the user to send a friend request to",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Friend request sent.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Friend request sent"),
     *             @OA\Property(property="data", ref="#/components/schemas/FriendshipResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error sending friend request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function sendRequest($friendId)
    {
        try {
            $user = auth()->user();

            $friendship = $this->friendshipRepository->sendFriendRequest($user, $friendId);
            broadcast(new FriendRequestSent(auth()->user()));

            return ApiResponse::sendResponse(201, 'Friend request sent', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/friend-request/accept/{userId}",
     *     summary="Accept a friend request",
     *     description="Accept a pending friend request from another user.",
     *     tags={"Friendships"},
     *     @OA\Parameter(
     *         name="friendId",
     *         in="path",
     *         description="ID of the user whose friend request is accepted",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request accepted.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Friend request accepted"),
     *             @OA\Property(property="data", ref="#/components/schemas/FriendshipResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error accepting friend request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function acceptRequest($friendId)
    {
        try {
            $user = auth()->user();

            $friendship = $this->friendshipRepository->acceptFriendRequest($user, $friendId);

            return ApiResponse::sendResponse(200, 'Friend request accepted', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/friend-request/reject/{userId}",
     *     summary="Reject a friend request",
     *     description="Reject a pending friend request from another user.",
     *     tags={"Friendships"},
     *     @OA\Parameter(
     *         name="friendId",
     *         in="path",
     *         description="ID of the user whose friend request is rejected",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request rejected.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Friend request rejected"),
     *             @OA\Property(property="data", ref="#/components/schemas/FriendshipResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error rejecting friend request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function rejectRequest($friendId)
    {
        try {
            $user = auth()->user();

            $friendship = $this->friendshipRepository->rejectFriendRequest($user, $friendId);

            return ApiResponse::sendResponse(200, 'Friend request rejected', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/friends",
     *     summary="List all friends",
     *     description="Retrieve a list of all the friends of the authenticated user.",
     *     tags={"Friendships"},
     *     @OA\Response(
     *         response=200,
     *         description="Friends list retrieved.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Friends list retrieved"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving friends list.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function listFriends()
    {
        try {
            $user = auth()->user();

            $friends = $this->friendshipRepository->getFriendsList($user);

            return ApiResponse::sendResponse(200, 'Friends list retrieved', UserResource::collection($friends));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/friends/pending",
     *     summary="List all pending friend requests",
     *     description="Retrieve a list of all pending friend requests for the authenticated user.",
     *     tags={"Friendships"},
     *     @OA\Response(
     *         response=200,
     *         description="Pending friend requests retrieved.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Pending friend requests retrieved"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FriendshipResource"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving pending requests.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function pendingRequests()
    {
        try {
            $user = auth()->user();

            $pendingRequests = $this->friendshipRepository->getPendingRequests($user);

            return ApiResponse::sendResponse(200, 'Pending friend requests retrieved', FriendshipResource::collection($pendingRequests));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
