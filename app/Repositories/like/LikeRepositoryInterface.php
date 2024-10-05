<?php

namespace App\Repositories\like;

interface LikeRepositoryInterface
{
    public function toggleLike($postId, $userId);
}
