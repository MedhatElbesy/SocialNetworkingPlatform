<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use UploadImageTrait;
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
            $imagePath = $this->uploadImage($request, 'image', 'posts');

            $post = $this->postRepository->createPost([
                'user_id' => auth()->id(),
                'content' => $validatedData['content'],
                'image' => 'http://localhost:8000/storage/'. $imagePath,
            ]);

        return redirect()->route('feeds.index')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = $this->postRepository->findPostById($id);
        return view('posts.show', compact('post'));
    }

}
