<?php

namespace App\Libs;

use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Models\Blog\Author;
use App\Libs\Models\Blog\Category;
use App\Libs\Models\Blog\Tag;
use App\Libs\Models\Blog\Post;

/**
 * WordPress API integration class for blog functionality
 * 
 * This class implements the BlogInterface to provide WordPress-specific
 * blog operations including post retrieval, categorization, and tagging.
 */
class Writer implements BlogInterface
{
    /** @var array Configuration settings */
    private $config = [];

    /** @var CacheInterface Cache service for storing API responses */
    private CacheInterface $cache;

    /** @var DataStoreInterface Database connection */
    private DataStoreInterface $db;

    /** @var bool Whether caching is enabled */
    private $cacheEnabled;

    /** @var int Cache TTL in seconds */
    private $cacheAge;

    /** @var string Default language for content */
    private $defaultLang;

    /** @var array Default API request options */
    private $options;

    /**
     * Constructor
     * 
     * @param array $config Configuration array containing WordPress API settings
     * @param CacheInterface $cache Cache service for storing API responses
     * @param DataStoreInterface $db Database connection
     */
    public function __construct(
        $config,
        CacheInterface $cache,
        DataStoreInterface $db
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->cacheAge = $this->config['cache']['ttl'];
        $this->cacheEnabled = $this->config['blog']['enable_cache'];
        $this->defaultLang = $this->config['lang']['default'];
        $this->options = [
            '_embed' => true,
            'page' => 1,
            'per_page' => 5,
            'offset' => 0,
            'lang' => $this->defaultLang,
        ];

        $this->db = $db;
    }
    
    /**
     * Retrieve posts from WordPress API with optional filtering
     * 
     * @param array $options Additional query options for the API request
     * @return array Array of Post objects
     */
    private function retrievePosts(array $options = []): array
    {
        $posts = [];
        $useOptions = array_merge($this->options, $options);

        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey('posts', $useOptions);
            $cachedPosts = $this->cache->get($cacheKey);
            if ($cachedPosts) {
                return $cachedPosts;
            }
        }

        $pdo = $this->db->getPdo();

        if ($useOptions['categories']) {
            $response = $pdo->query("SELECT * FROM blogs WHERE language = '{$this->defaultLang}' 
            AND category_id IN (".implode(",", $useOptions['categories']).") 
            LIMIT {$useOptions['per_page']} OFFSET {$useOptions['offset']}")->fetchAll();
        } else if ($useOptions['tags']) {
            $tags = $pdo->query(
                "SELECT * FROM tags_blogs WHERE tag_id IN (".implode(",", $useOptions['tags']).")"
            )->fetchAll();
            
            $blogIds = array_map(function($tag) { 
                return $tag['blog_id']; }, $tags
            );
            
            $response = $pdo->query(
                "SELECT * FROM blogs WHERE language = '{$this->defaultLang}' AND id IN (".implode(",", $blogIds).") LIMIT {$useOptions['per_page']} OFFSET {$useOptions['offset']}"
            )->fetchAll();
        } else {
            $response = $pdo->query("SELECT * FROM blogs WHERE language = '{$this->defaultLang}' LIMIT {$useOptions['per_page']} OFFSET {$useOptions['offset']}")->fetchAll();
        }

        // construct blog post
        foreach ($response as $post) {
            $posts[] = $this->constructPost($post);
        }

        if ($this->cacheEnabled) {
            $this->cache->set($cacheKey, $posts, $this->cacheAge);
        }

        return $posts;
    }

    /**
     * Retrieve a single post by ID from WordPress API
     * 
     * @param int $id Post ID
     * @return Post Post object
     */
    private function retrieveSinglePost(int $id): Post
    {
        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey('post', $id);
            $cachedPost = $this->cache->get($cacheKey);
            if ($cachedPost) {
                return $cachedPost;
            }
        }

        $pdo = $this->db->getPdo();
        $response = $pdo->query("SELECT * FROM blogs WHERE id = {$id}")->fetch();
        $post = $response->fetch();
        
        if ($this->cacheEnabled) {
            $this->cache->set($cacheKey, $post, $this->cacheAge);
        }
        return $this->constructPost($post);
    }

    /**
     * Construct a Post object from WordPress API response data
     * 
     * @param array $post Raw post data from API
     * @return Post Constructed Post object
     */
    private function constructPost($post)
    {
        $pdo = $this->db->getPdo();
        // get author
        $author = new Author(
            "1",
            "Admin",
            "https://raw.githubusercontent.com/8grams/homie/refs/heads/develop/assets/logo.png",
        );

        // get categories
        $categories = [];
        $categories = $pdo->query("SELECT * FROM categories WHERE id = {$post['category_id']}")->fetchAll();

        // get tags
        $tags = [];
        $tags = $pdo->query("SELECT * FROM tags_blogs WHERE blog_id = {$post['id']}")->fetchAll();

        return new Post(
            $post['id'],
            $post['title'],
            $categories,
            $tags,
            $post['excerpt'],
            $post['content'],
            $author,
            date("d M Y", strtotime($post['date'])),
            $post['slug'],
            $post['hero_image'],
            $post['slug']
        );
    }

    /**
     * Get all posts with optional filtering
     * 
     * @param array $options Additional query options
     * @return array Array of Post objects
     */
    public function getPosts(array $options = []): array
    {
        return $this->retrievePosts($options);
    }

    /**
     * Get a single post by ID
     * 
     * @param int $id Post ID
     * @return Post Post object
     */
    public function getPostById(int $id): Post
    {
        return $this->retrieveSinglePost($id);
    }

    /**
     * Get posts filtered by category ID
     * 
     * @param int $categoryId Category ID
     * @param array $options Additional query options
     * @return array Array of Post objects
     */
    public function getPostsByCategory(int $categoryId, array $options = []): array
    {
        $options['categories'] = [$categoryId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    /**
     * Get posts filtered by tag ID
     * 
     * @param int $tagId Tag ID
     * @param array $options Additional query options
     * @return array Array of Post objects
     */
    public function getPostsByTag(int $tagId, array $options = []): array
    {
        $options['tags'] = [$tagId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    /**
     * Get posts filtered by author ID
     * 
     * @param int $authorId Author ID
     * @param array $options Additional query options
     * @return array Array of Post objects
     */
    public function getPostsByAuthor(int $authorId, array $options = []): array
    {
        // $options['author'] = [$authorId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    /**
     * Generate a cache key for API requests
     * 
     * @param string $url API endpoint URL
     * @param array $options Query options
     * @return string SHA1 hash of URL and options
     */
    private function getCacheKey($url, $options)
    {
        return sha1($url . serialize($options));
    }

    /**
     * Get all categories from WordPress
     * 
     * @return array Array of Category objects
     */
    public function getCategories(): array
    {
        $categories = [];

        $pdo = $this->db->getPdo();
        $response = $pdo->query("SELECT * FROM categories")->fetchAll();
        foreach ($response as $category) {
            $categories[] = new Category($category['id'], $category['name'], $category['slug']);
        }
        return $categories;
    }

    /**
     * Get all tags from WordPress
     * 
     * @return array Array of Tag objects
     */
    public function getTags(): array
    {
        $tags = [];
        $pdo = $this->db->getPdo();
        $response = $pdo->query("SELECT * FROM tags")->fetchAll();
        foreach ($response as $tag) {
            $tags[] = new Tag($tag['id'], $tag['name'], $tag['slug']);
        }
        return $tags;
    }
}

