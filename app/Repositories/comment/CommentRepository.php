<?php

namespace App\Repositories\Comment;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function getAllCommentsByPostId($postId)
    {
        return Comment::with('user')->where('post_id', $postId)->get();
    }

    public function findCommentById($id)
    {
        return Comment::findOrFail($id);
    }

    public function createComment(array $data)
    {
        return Comment::create($data);
    }

    public function updateComment($comment, array $data)
    {
        return $comment->update($data);
    }

    public function deleteComment($comment)
    {
        return $comment->delete();
    }
}
