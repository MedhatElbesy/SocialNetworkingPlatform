<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

use App\Models\Post;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function store(StoreComment $request, $postId)
    {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['post_id'] = $postId;

            $comment = $this->commentRepository->createComment($data);
        return redirect()->route('posts.show', $postId)->with('success', 'Comment added successfully');
    }


}
