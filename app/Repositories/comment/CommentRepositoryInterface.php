<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function getAllCommentsByPostId($postId);

    public function findCommentById($id);

    public function createComment(array $data);

    public function updateComment($comment, array $data);

    public function deleteComment($comment);
}
