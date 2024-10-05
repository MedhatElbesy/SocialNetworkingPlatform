<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\user\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function show($id)
    {

        $user = $this->userRepository->getUserById($id);
        $posts = $user->posts;

        return view('user.profile', compact('user', 'posts'));
    }

}
