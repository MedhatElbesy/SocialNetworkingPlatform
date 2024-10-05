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
