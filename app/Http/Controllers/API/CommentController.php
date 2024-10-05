<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Comment\CommentRepositoryInterface;
use Exception;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index($postId)
    {
        try {
            Post::findOrFail($postId);

            $comments = $this->commentRepository->getAllCommentsByPostId($postId);

            return ApiResponse::sendResponse(200, 'Comments retrieved successfully.', CommentResource::collection($comments));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error retrieving comments: ' . $e->getMessage());
        }
    }

    public function store(StoreCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $comment = $this->commentRepository->createComment($data);

            return ApiResponse::sendResponse(201, 'Comment created successfully.', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error creating comment: ' . $e->getMessage());
        }
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        try {
            $comment = $this->commentRepository->findCommentById($id);
            $data = $request->validated();

            $this->commentRepository->updateComment($comment, $data);

            return ApiResponse::sendResponse(200, 'Comment updated successfully.', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error updating comment: ' . $e->getMessage());
        }
    }

    public function destroy($commentId)
    {
        try {
            $comment = $this->commentRepository->findCommentById($commentId);

            $this->commentRepository->deleteComment($comment);

            return ApiResponse::sendResponse(204, 'Comment deleted successfully.');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error deleting comment: ' . $e->getMessage());
        }
    }
}
