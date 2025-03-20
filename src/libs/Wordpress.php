<?php

namespace App\Libs;

use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Models\Blog\Author;
use App\Libs\Models\Blog\Category;
use App\Libs\Models\Blog\Tag;
use App\Libs\Models\Blog\Post;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Wordpress implements BlogInterface
{
    private $config = [];
    private CacheInterface $cache;
    private HttpClientInterface $client;
    private $cacheEnabled;
    private $cacheAge;
    private $defaultLang;
    private $options;

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

    private function retrieveSinglePost(int $id): Post
    {
        $response = $this->client->request('GET', 'posts/' . $id, ['query' => ['_embed' => true]]);
        $post = $response->toArray();
        return $this->constructPost($post);
    }

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

    public function getPosts(array $options = []): array
    {
        return $this->retrievePosts($options);
    }

    public function getPostById(int $id): Post
    {
        return $this->retrieveSinglePost($id);
    }

    public function getPostsByCategory(int $categoryId, array $options = []): array
    {
        $options['categories'] = [$categoryId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    public function getPostsByTag(int $tagId, array $options = []): array
    {
        $options['tags'] = [$tagId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    public function getPostsByAuthor(int $authorId, array $options = []): array
    {
        $options['author'] = [$authorId];
        $useOptions = array_merge($this->options, $options);
        return $this->retrievePosts($useOptions);
    }

    private function getCacheKey($url, $options)
    {
        return sha1($url . serialize($options));
    }

    public function getCategories(): array
    {
        $categories = [];
        $response = $this->client->request('GET', 'categories');
        foreach ($response->toArray() as $category) {
            $categories[] = new Category($category['id'], $category['name'], $category['slug']);
        }
        return $categories;
    }

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

