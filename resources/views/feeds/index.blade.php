@extends('layouts.app')
@section('title', 'News Feed')

@section('content')
<h1>News Feed</h1>
@foreach ($posts as $post)
    <div class="post">
    <div class="post-header">
        <h3>{{ $post->user->name }}</h3>
        <p class="post-date">{{ $post->created_at->format('M d, Y') }}</p>
    </div>

    <p class="post-content">{{ $post->content }}</p>

    @if ($post->image)
        <img class="post-image" src="{{ asset($post->image) }}" alt="Post Image">
    @endif

    <div class="post-actions">
        <a class="view-post" href="{{ route('posts.show', $post->id) }}">View Post</a>
        <p class="post-stats">Likes: {{ $post->likes_count }} | Comments: {{ $post->comments_count }}</p>

        <form action="{{ route('likes.toggle', $post->id) }}" method="POST" class="like-form">
            @csrf
            <button type="submit" class="like-button">
                @if ($post->likes->contains('user_id', Auth::id()))
                    Unlike
                @else
                    Like
                @endif
            </button>
        </form>
    </div>

    <div class="comments-section">
        <h4>Comments:</h4>
        @if ($post->comments->isEmpty())
            <p class="no-comments">No comments yet.</p>
        @else
            @foreach ($post->comments as $comment)
                <div class="comment">
                    <strong>{{ $comment->user->name }}:</strong>
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Add a comment form -->
    <div class="add-comment-form">
        <h5>Add a Comment:</h5>
        <form action="{{ route('comments.store', $post->id) }}" method="POST">
            @csrf
            <textarea name="content" required placeholder="Write a comment..."></textarea>
            <button type="submit">Comment</button>
        </form>
    </div>
</div>

@endforeach
@endsection



<style>


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


 .post {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-content {
    margin: 10px 0;
}

.post-image {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.post-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.post-stats {
    margin: 0;
}

.like-button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.like-button:hover {
    background-color: #0056b3;
}

.comments-section {
    margin-top: 15px;
    padding: 10px;
    background-color: #e9ecef;
    border-radius: 4px;
}

.comment {
    margin-bottom: 5px;
    padding: 5px;
    background-color: #f1f1f1;
    border-radius: 4px;
}

.add-comment-form {
    margin-top: 15px;
}

textarea {
    width: 100%;
    height: 60px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
}

.add-comment-form button {
    padding: 5px 10px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.add-comment-form button:hover {
    background-color: #218838;
}


</style>
