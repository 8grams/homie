<?php

namespace App\Libs\Interfaces;

use App\Libs\Models\Blog\Post;

/**
 * Interface for blog operations
 * 
 * This interface defines methods for:
 * - Retrieving blog posts with various filters
 * - Getting blog categories and tags
 */
interface BlogInterface
{
    /**
     * Get all blog posts with optional filtering
     * 
     * @param array $options Filtering options (e.g. limit, offset, status)
     * @return array Array of Post objects
     */
    public function getPosts(array $options = []): array;

    /**
     * Get a specific blog post by ID
     * 
     * @param int $id Post ID
     * @return Post Post object
     */
    public function getPostById(int $id): Post;

    /**
     * Get posts filtered by category
     * 
     * @param int $categoryId Category ID
     * @param array $options Additional filtering options
     * @return array Array of Post objects
     */
    public function getPostsByCategory(int $categoryId, array $options = []): array;

    /**
     * Get posts filtered by tag
     * 
     * @param int $tagId Tag ID
     * @param array $options Additional filtering options
     * @return array Array of Post objects
     */
    public function getPostsByTag(int $tagId, array $options = []): array;

    /**
     * Get posts filtered by author
     * 
     * @param int $authorId Author ID
     * @param array $options Additional filtering options
     * @return array Array of Post objects
     */
    public function getPostsByAuthor(int $authorId, array $options = []): array;

    /**
     * Get all blog categories
     * 
     * @return array Array of category objects
     */
    public function getCategories(): array;

    /**
     * Get all blog tags
     * 
     * @return array Array of tag objects
     */
    public function getTags(): array;
}