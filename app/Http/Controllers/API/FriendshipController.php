<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function sendRequest(Request $request, $friendId)
    {
        $user = auth()->user();
        $user->sendFriendRequest($friendId);
        return response()->json(['message' => 'Friend request sent successfully']);
    }

    public function acceptRequest(Request $request, $userId)
    {
        $user = auth()->user();
        $user->acceptFriendRequest($userId);
        return response()->json(['message' => 'Friend request accepted']);
    }

    public function rejectRequest(Request $request, $userId)
    {
        $user = auth()->user();
        $user->rejectFriendRequest($userId);
        return response()->json(['message' => 'Friend request rejected']);
    }

    public function listFriends(Request $request)
    {
        $user = auth()->user();
        $friends = $user->friends;
        return response()->json($friends);
    }

    public function listPendingRequests(Request $request)
    {
        $user = auth()->user();
        $requests = $user->receivedFriendRequests()->where('status', 'pending')->get();
        return response()->json($requests);
    }
}
