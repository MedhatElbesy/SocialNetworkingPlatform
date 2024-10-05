<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function getAllPosts();

    public function findPostById($id);

    public function createPost(array $data);

    public function updatePost($post, array $data);

    public function deletePost($post);

    public function getPostLikes($postId);
}
