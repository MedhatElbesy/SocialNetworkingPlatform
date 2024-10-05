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

    public function index()
    {
        try {
            $posts = $this->postRepository->getAllPosts();
            return ApiResponse::sendResponse(200, 'Posts retrieved successfully', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }

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

    public function show($id)
    {
        try {
            $post = $this->postRepository->findPostById($id);
            return ApiResponse::sendResponse(200, 'Post retrieved successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        }
    }

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
