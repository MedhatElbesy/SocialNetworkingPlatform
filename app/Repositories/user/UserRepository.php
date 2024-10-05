<?php

namespace App\Repositories\user;

use App\Models\Post;
use App\Models\User;
use App\Repositories\user\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(Request $request)
    {
        return User::where(function ($q) use ($request) {
            if ($request->search) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%");
            }
        })->get();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function updateUserProfile($request, $id)
    {
        DB::beginTransaction();

        try {
            if (Auth::id() !== (int) $id) {
                throw new Exception('You are not authorized to update this profile.', 403);
            }

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

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function uploadImage($request, $fieldName, $folder)
    {
        return $request->file($fieldName)->store($folder, 'public');
    }

    public function getTimeline()
    {
        return Post::withCount(['likes', 'comments'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }
}
