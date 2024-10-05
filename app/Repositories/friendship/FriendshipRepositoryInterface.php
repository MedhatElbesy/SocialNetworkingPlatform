<?php

namespace App\Repositories\Friendship;

interface FriendshipRepositoryInterface
{
    public function sendFriendRequest($user, $friendId);

    public function acceptFriendRequest($user, $friendId);

    public function rejectFriendRequest($user, $friendId);

    public function getFriendsList($user);

    public function getPendingRequests($user);
}
