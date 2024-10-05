<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register($data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function createToken(User $user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
