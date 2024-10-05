@extends('layouts.app')

@section('title', 'Post Details')

@section('content')
    <div class="post-details">
        <h1>{{ $post->user->name }}'s Post</h1>
        <p class="post-content">{{ $post->content }}</p>

        @if ($post->image)
            <img class="post-image" src="{{ asset($post->image) }}" alt="Post Image">
        @endif

        <h2>Comments</h2>
        <div class="comments-section">
            @foreach ($post->comments as $comment)
                <div class="comment">
                    <strong>{{ $comment->user->name }}:</strong>
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>

        <h3>Add a Comment</h3>
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="comment-form">
            @csrf
            <textarea name="content" required placeholder="Write your comment here..."></textarea>
            <button type="submit">Comment</button>
        </form>
    </div>
@endsection


<style>
    .post-details {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.post-content {
    font-size: 18px;
    margin-bottom: 20px;
}

.post-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
    border-radius: 8px;
}

.comments-section {
    margin: 20px 0;
    padding: 10px;
    background-color: #e9ecef;
    border-radius: 8px;
}

.comment {
    margin-bottom: 10px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
}

.comment-form {
    margin-top: 20px;
}

.comment-form textarea {
    width: 100%;
    height: 80px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: none;
}

.comment-form button {
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.comment-form button:hover {
    background-color: #0056b3;
}

</style>
