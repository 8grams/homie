<?php

namespace App\Libs;

use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Models\Blog\Author;
use App\Libs\Models\Blog\Category;
use App\Libs\Models\Blog\Tag;
use App\Libs\Models\Blog\Post;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * WordPress API integration class for blog functionality
 * 
 * This class implements the BlogInterface to provide WordPress-specific
 * blog operations including post retrieval, categorization, and tagging.
 */
class Wordpress implements BlogInterface
{
    /** @var array Configuration settings */
    private $config = [];

    /** @var CacheInterface Cache service for storing API responses */
    private CacheInterface $cache;

    /** @var HttpClientInterface HTTP client for API requests */
    private HttpClientInterface $client;

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
     * @param HttpClientInterface $client HTTP client for making API requests
     */
    public function __construct(
        $config,
        CacheInterface $cache,
        HttpClientInterface $client
    ) {
        $this->config = $config;
        $this->client = $client->withOptions([
            'base_uri' => $config['blog']['url'],
            'auth_basic' => [$config['blog']['username'], $this->config['blog']['password']],
        ]);

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
        }

        $response = $this->client->request('GET', 'posts', ['query' => $useOptions]);

        // construct blog post
        foreach ($response->toArray() as $post) {
            $posts[] = $this->constructPost($post);
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
        $response = $this->client->request('GET', 'posts/' . $id, ['query' => ['_embed' => true]]);
        $post = $response->toArray();
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
        $author = new Author(
            $post['_embedded']['author'][0]['id'],
            $post['_embedded']['author'][0]['name'],
            $post['_embedded']['author'][0]['avatar_urls']['96']
        );

        $categories = [];
        $blogc = $post['_embedded']['wp:term'][0];
        foreach ($blogc as $category) {
            $categories[] = new Category($category['id'], $category['name'], $category['slug']);
        }

        $tags = [];
        $blogt = $post['_embedded']['wp:term'][1];
        foreach ($blogt as $tag) {
            $tags[] = new Tag($tag['id'], $tag['name'], $tag['slug']);
        }

        return new Post(
            $post['id'],
            $post['title']['rendered'],
            $categories,
            $tags,
            $post['excerpt']['rendered'],
            $post['content']['rendered'],
            $author,
            date("d M Y", strtotime($post['date'])),
            $post['link'],
            isset($post['_embedded']['wp:featuredmedia']) ? $post['_embedded']['wp:featuredmedia'][0]['source_url'] : "https://raw.githubusercontent.com/8grams/homie/refs/heads/develop/assets/logo.png",
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
        $options['author'] = [$authorId];
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
        $response = $this->client->request('GET', 'categories');
        foreach ($response->toArray() as $category) {
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
        $response = $this->client->request('GET', 'tags');
        foreach ($response->toArray() as $tag) {
            $tags[] = new Tag($tag['id'], $tag['name'], $tag['slug']);
        }
        return $tags;
    }
}

