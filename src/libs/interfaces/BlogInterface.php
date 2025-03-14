<?php

namespace App\Libs\Interfaces;

interface BlogInterface
{
    public function getHighlight(array $options = []): array;
    public function getPosts(array $options = []): array;
    public function getPostBySlug(string $slug): array;
    public function getPostById(int $id): array;
}