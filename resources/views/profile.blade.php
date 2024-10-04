@extends('layouts.app')

@section('content')
<div class="profile">
    <h1>{{ $user->name }}'s Profile</h1>
    <p>{{ $user->bio }}</p>

    <h2>Posts</h2>
    @if($posts->isEmpty())
        <p>No posts available.</p>
    @else
        <ul>
            @foreach($posts as $post)
                <li>
                    <div>
                        <strong>{{ $post->created_at->format('M d, Y') }}</strong>
                        <p>{{ $post->content }}</p>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" style="max-width: 100%; height: auto;">
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
