@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <div class="create-post">
        <h1>Create a New Post</h1>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form">
            @csrf
            <div class="form-group">

            <textarea id="content" name="content" required placeholder="Write your post content here..."></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="submit-button">Create Post</button>
        </form>
    </div>
@endsection


<style>
    .create-post {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    margin-bottom: 20px;
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: none;
}

input[type="file"] {
    display: block;
    margin-top: 5px;
}

.submit-button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #0056b3;
}

</style>
