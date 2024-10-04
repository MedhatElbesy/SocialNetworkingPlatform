@extends('layouts.app')

@section('title', "{$user->name}'s Profile")

@section('content')
    <div class="profile-container">
        <h1>{{ $user->name }}'s Profile</h1>
        <div class="profile-header">
            <img class="profile-image" src="{{ asset($user->image) }}" alt="Profile Image">
            <p class="bio">{{ $user->bio }}</p>
        </div>
        <h2>Posts</h2>
        <div class="posts-container">
            @foreach ($user->posts as $post)
                <div class="post">
                    <p class="post-content">{{ $post->content }}</p>
                    @if ($post->image)
                        <img class="post-image" src="{{ asset($post->image) }}" alt="Post Image">
                    @endif
                    <a class="view-post" href="{{ route('posts.show', $post->id) }}">View Post</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection


<style>
    .profile-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
    object-fit: cover;
}

.bio {
    font-size: 16px;
    color: #555;
}

.posts-container {
    margin-top: 20px;
}

.post {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #e9ecef;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.post-content {
    margin-bottom: 10px;
}

.post-image {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.view-post {
    display: inline-block;
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.view-post:hover {
    background-color: #0056b3;
}

</style>
