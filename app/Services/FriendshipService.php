<?php

namespace App\Services;

use App\Models\User;

class FriendshipService
{
    public function sendRequest(User $userFrom, User $userTo)
    {
        return $userFrom->friendships()->attach($userTo->id, ['status' => 'pending']);
    }

    public function acceptRequest(User $userFrom, User $userTo)
    {
        $userFrom->friendships()->updateExistingPivot($userTo->id, ['status' => 'accepted']);
    }

    public function rejectRequest(User $userFrom, User $userTo)
    {
        $userFrom->friendships()->updateExistingPivot($userTo->id, ['status' => 'rejected']);
    }

    public function getFriends(User $user)
    {
        return $user->friendships()->where('status', 'accepted')->with('friend')->get();
    }
}
