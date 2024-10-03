<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use UploadImageTrait;
    public function register(RegisterRequest $request)
    {
        $data = $request->only('name','email','password');
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request, 'image', 'profile_pictures');
            }
        $user = User::create($data);

        $user->token = $user->createToken('Api Token')->plainTextToken;
        return ApiResponse::sendResponse(201, "Created Successfully", new UserResource($user));
    }
}
