<?php

namespace App\Http\Controllers\API;

use App\Events\CommentAdded;
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

    /**
     * @OA\Get(
     *     path="/posts/{postId}/comments",
     *     summary="Get all comments by post ID",
     *     description="Retrieve all comments for a specific post.",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comments retrieved successfully.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="This is a comment."),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="post_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-05 12:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-05 12:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=500, description="Error retrieving comments")
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/comments",
     *     summary="Create a new comment",
     *     description="Create a new comment on a post.",
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="This is a comment."),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="This is a comment."),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="post_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-05 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-05 12:00:00")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error creating comment")
     * )
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $comment = $this->commentRepository->createComment($data);
            broadcast(new CommentAdded($comment));

            return ApiResponse::sendResponse(201, 'Comment created successfully.', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Error creating comment: ' . $e->getMessage());
        }
    }


    /**
     * @OA\Put(
     *     path="/comments/{id}",
     *     summary="Update a comment",
     *     description="Update a specific comment by ID.",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Updated comment text.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Updated comment text."),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="post_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-05 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-05 12:00:00")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=500, description="Error updating comment")
     * )
     */
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


    /**
     * @OA\Delete(
     *     path="/comments/{id}",
     *     summary="Delete a comment",
     *     description="Delete a specific comment by ID.",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted successfully."
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=500, description="Error deleting comment")
     * )
     */
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
