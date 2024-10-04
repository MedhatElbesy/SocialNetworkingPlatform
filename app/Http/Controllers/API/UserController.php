<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index()
    {
        try {
            $users = User::all();

            return ApiResponse::sendResponse(200, 'Users retrieved successfully', UserResource::collection($users));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'An error occurred: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return ApiResponse::sendResponse(200, 'User retrieved successfully', new UserResource($user));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404, 'User not found');
        }
    }

    public function update(UpdateUserProfileRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $oldProfileImage = $user->image;

            if ($request->hasFile('image')) {
                $profileImagePath = $this->uploadImage($request, 'image', 'profile_images');

                if ($oldProfileImage && Storage::disk('public')->exists($oldProfileImage)) {
                    Storage::disk('public')->delete($oldProfileImage);
                }

                $user->image = $profileImagePath;
            }

            $user->update($request->only('name', 'email', 'bio'));

            DB::commit();

            return ApiResponse::sendResponse(200, 'User updated successfully', new UserResource($user));
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::sendResponse(500, 'An error occurred: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->get();

        return response()->json($users, 200);
    }

    public function getTimeline()
    {
        try {
            $user = auth()->user();

            $userPosts = Post::where('user_id', $user->id);

            $friendIds = $user->friendships()->wherePivot('status', 'accepted')->pluck('friend_id')->toArray();
            $friendPosts = Post::whereIn('user_id', $friendIds);

            $timelinePosts = $userPosts->union($friendPosts)->orderBy('created_at', 'desc')->paginate(10);

            return ApiResponse::sendResponse(200, 'Timeline fetched successfully', PostResource::collection($timelinePosts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, $e->getMessage());
        }
    }
}
