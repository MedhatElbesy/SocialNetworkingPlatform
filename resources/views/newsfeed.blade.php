@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>News Feed</h2>

        <!-- Post creation form -->
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <textarea name="content" class="form-control" placeholder="What's on your mind?" required></textarea>
            <div class="d-flex align-items-center mt-2">
                <input type="file" name="image" class="form-control-file">
                <button type="submit" class="btn btn-primary ml-auto">Post</button>
            </div>
        </form>

        <!-- Display posts -->
        @foreach ($posts as $post)
            <div class="post mt-4">
                <h4>{{ $post->user->name }}</h4>
                <p>{{ $post->content }}</p>
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid">
                @endif

                <!-- Like and Comment options -->
                <div class="mt-2">
                    <form action="{{ route('post.like', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">{{ $post->isLikedByUser ? 'Unlike' : 'Like' }}</button>
                    </form>

                    <form action="{{ route('post.comment', $post->id) }}" method="POST">
                        @csrf
                        <textarea name="comment" class="form-control mt-2" placeholder="Add a comment"></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Comment</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
