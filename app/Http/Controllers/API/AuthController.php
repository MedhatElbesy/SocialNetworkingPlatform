<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\AuthService;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $user->token = $this->authService->createToken($user);

        return ApiResponse::sendResponse(201, "Created Successfully", new UserResource($user));
    }

    public function login(LoginRequest $request)
    {
        if (!$this->authService->login($request->only('email', 'password'))) {
            return ApiResponse::sendResponse(401, 'Invalid credentials');
        }

        $user = Auth::user();
        $user->token = $this->authService->createToken($user);

        return ApiResponse::sendResponse(200, 'login success', new UserResource($user));
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        return ApiResponse::sendResponse(200, 'Logout Success');
    }
}
