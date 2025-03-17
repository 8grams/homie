<?php

namespace App\Libs\Interfaces;

use App\Libs\Models\Blog\Post;

interface BlogInterface
{
    public function getPosts(array $options = []): array;
    public function getPostById(int $id): Post;
}