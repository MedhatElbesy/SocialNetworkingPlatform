<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
// use App\Repositories\post\PostRepositoryInterface;
use App\Traits\UploadImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use UploadImageTrait;

    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    /**
     * @OA\Get(
     *     path="/post",
     *     tags={"Posts"},
     *     summary="Retrieve all posts",
     *     description="Returns a list of all posts",
     *     operationId="getAllPosts",
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        try {
            $posts = $this->postRepository->getAllPosts();
            return ApiResponse::sendResponse(200, 'Posts retrieved successfully', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }



    /**
 * @OA\Post(
 *     path="/post",
 *     tags={"Posts"},
 *     summary="Create a new post",
 *     description="Create a new post with content and image",
 *     operationId="createPost",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"content", "image"},
 *             @OA\Property(property="content", type="string", example="This is my new post"),
 *             @OA\Property(property="image", type="string", format="binary")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Post created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Post created successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Post")
 *         )
 *     )
 * )
 */

    public function store(StorePostRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $imagePath = $this->uploadImage($request, 'image', 'posts');

            $post = $this->postRepository->createPost([
                'user_id' => auth()->id(),
                'content' => $validatedData['content'],
                'image' => $imagePath,
            ]);

            return ApiResponse::sendResponse(201, 'Post created successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/post/{id}",
     *     tags={"Posts"},
     *     summary="Get a single post",
     *     description="Retrieve a single post by its ID",
     *     operationId="getPostById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $post = $this->postRepository->findPostById($id);
            return ApiResponse::sendResponse(200, 'Post retrieved successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        }
    }


    /**
     * @OA\Put(
     *     path="/post/{id}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     description="Update the content or image of an existing post",
     *     operationId="updatePost",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "image"},
     *             @OA\Property(property="content", type="string", example="Updated post content"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     )
     * )
     */

    public function update(StorePostRequest $request, $id)
    {
        try {
            $post = $this->postRepository->findPostById($id);
            $validatedData = $request->validated();
            $imagePath = $this->uploadImage($request, 'image', 'posts');

            $this->postRepository->updatePost($post, [
                'content' => $validatedData['content'],
                'image' => $imagePath
            ]);

            return ApiResponse::sendResponse(200, 'Post updated successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }


    /**
     * @OA\Delete(
     *     path="/post/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     description="Delete a post by its ID",
     *     operationId="deletePost",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $post = $this->postRepository->findPostById($id);
            $this->postRepository->deletePost($post);

            return ApiResponse::sendResponse(200, 'Post deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

    public function showPostLikes($postId)
    {
        try {
            $likes = $this->postRepository->getPostLikes($postId);
            return ApiResponse::sendResponse(200, 'Likes retrieved successfully', LikeResource::collection($likes));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
