<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function sendRequest($friendId)
    {
        try {
            $user = auth()->user();

            if ($user->id == $friendId) {
                return ApiResponse::sendResponse(400, 'You cannot send a friend request to yourself.');
            }
            $friend = User::findOrFail($friendId);

            $existingRequest = Friendship::where(function ($query) use ($user, $friend) {
                $query->where('user_id', $user->id)->where('friend_id', $friend->id);
            })->orWhere(function ($query) use ($user, $friend) {
                $query->where('user_id', $friend->id)->where('friend_id', $user->id);
            })->first();

            if ($existingRequest) {
                return ApiResponse::sendResponse(409, 'Friend request already exists or you are already friends');
            }

            $friendship = Friendship::create([
                'user_id' => $user->id,
                'friend_id' => $friend->id,
                'status' => 'pending'
            ]);

            return ApiResponse::sendResponse(201, 'Friend request sent', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    public function acceptRequest($friendId)
    {
        try {
            $user = auth()->user();
            $friendship = Friendship::where('user_id', $friendId)
                ->where('friend_id', $user->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $friendship->update(['status' => 'accepted']);

            return ApiResponse::sendResponse(200, 'Friend request accepted', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    public function rejectRequest($friendId)
    {
        try {
            $user = auth()->user();
            $friendship = Friendship::where('user_id', $friendId)
                ->where('friend_id', $user->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $friendship->update(['status' => 'rejected']);

            return ApiResponse::sendResponse(200, 'Friend request rejected', new FriendshipResource($friendship));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    public function listFriends()
    {
        try {
            $user = auth()->user();

            $friends = User::whereHas('friendships', function ($query) use ($user) {
                $query->where('status', 'accepted')
                        ->where(function ($q) use ($user) {
                            $q->where('user_id', $user->id)
                            ->orWhere('friend_id', $user->id);
                        });
            })->get();

            return ApiResponse::sendResponse(200, 'Friends list retrieved', UserResource::collection($friends));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    public function pendingRequests()
    {
        try {
            $user = auth()->user();

            $pendingRequests = Friendship::where('friend_id', $user->id)
                ->where('status', 'pending')
                ->get();

            return ApiResponse::sendResponse(200, 'Pending friend requests retrieved', FriendshipResource::collection($pendingRequests));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
