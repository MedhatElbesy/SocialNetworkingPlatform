<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index($post_id)
    {
        try {
            Post::findOrFail($post_id);

            $comments = Comment::with('user')
                ->where('post_id', $post_id)
                ->get();

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

            $comment = Comment::create($data);

            return ApiResponse::sendResponse(201, 'Comment created successfully.',new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error creating comment: ' . $e->getMessage());
        }
    }
    public function update(UpdateCommentRequest $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $data = $request->validated();

            $comment->update($data);

            return ApiResponse::sendResponse(200, 'Comment updated successfully.', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error updating comment: ' . $e->getMessage());
        }
    }

    public function destroy($comment_id)
    {
        try {
            $comment = Comment::findOrFail($comment_id);
            $comment->delete();

            return ApiResponse::sendResponse(204, 'Comment deleted successfully.'); // Use ApiResponse
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error deleting comment: ' . $e->getMessage()); // Handle exceptions
        }
    }
}
