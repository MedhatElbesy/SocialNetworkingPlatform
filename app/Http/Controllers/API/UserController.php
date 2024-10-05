<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Repositories\user\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAllUsers($request);

            if ($users->isNotEmpty()) {
                return ApiResponse::sendResponse(200, 'Users retrieved successfully', UserResource::collection($users));
            } else {
                return ApiResponse::sendResponse(404, 'No users found');
            }
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'An error occurred: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userRepository->getUserById($id);
            return ApiResponse::sendResponse(200, 'User retrieved successfully', new UserResource($user));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404, 'User not found');
        }
    }

    public function update(UpdateUserProfileRequest $request, $id)
    {
        try {
            $user = $this->userRepository->updateUserProfile($request, $id);
            return ApiResponse::sendResponse(200, 'User updated successfully', new UserResource($user));
        } catch (Exception $e) {
            return ApiResponse::sendResponse($e->getCode() ?: 500, 'An error occurred: ' . $e->getMessage());
        }
    }

    public function getTimeline()
    {
        try {
            $posts = $this->userRepository->getTimeline();
            return ApiResponse::sendResponse(200, 'Timeline fetched successfully', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
