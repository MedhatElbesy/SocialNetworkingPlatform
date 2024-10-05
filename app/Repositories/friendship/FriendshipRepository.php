<?php

namespace App\Repositories\Friendship;

use App\Models\Friendship;
use App\Models\User;
use Exception;

class FriendshipRepository implements FriendshipRepositoryInterface
{
    public function sendFriendRequest($user, $friendId)
    {
        if ($user->id == $friendId) {
            throw new Exception('You cannot send a friend request to yourself.');
        }

        $friend = User::findOrFail($friendId);

        $existingRequest = Friendship::where(function ($query) use ($user, $friend) {
            $query->where('user_id', $user->id)->where('friend_id', $friend->id);
        })->orWhere(function ($query) use ($user, $friend) {
            $query->where('user_id', $friend->id)->where('friend_id', $user->id);
        })->first();

        if ($existingRequest) {
            throw new Exception('Friend request already exists or you are already friends.');
        }

        return Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => 'pending',
        ]);
    }

    public function acceptFriendRequest($user, $friendId)
    {
        $friendship = Friendship::where('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);

        return $friendship;
    }

    public function rejectFriendRequest($user, $friendId)
    {
        $friendship = Friendship::where('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update(['status' => 'rejected']);

        return $friendship;
    }

    public function getFriendsList($user)
    {
        return User::whereHas('friendships', function ($query) use ($user) {
            $query->where('status', 'accepted')
                  ->where(function ($q) use ($user) {
                      $q->where('user_id', $user->id)
                        ->orWhere('friend_id', $user->id);
                  });
        })->get();
    }

    public function getPendingRequests($user)
    {
        return Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->get();
    }
}
