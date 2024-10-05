<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\user\UserRepositoryInterface;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index()
    {
        $posts = $this->userRepository->getTimeline();
        return view('feeds.index', compact('posts'));
    }
}
