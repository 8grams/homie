<?php

namespace App\Libs\Interfaces;

use App\Libs\Models\Blog\Post;

interface BlogInterface
{
    public function getPosts(array $options = []): array;
    public function getPostById(int $id): Post;
    public function getPostsByCategory(int $categoryId, array $options = []): array;
    public function getPostsByTag(int $tagId, array $options = []): array;
    public function getPostsByAuthor(int $authorId, array $options = []): array;
}