<?php

namespace App\Libs;

use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
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
    
    public function getPosts(array $options = []): array
    {
        return [];
    }

    public function getPostBySlug(string $slug): array
    {
        return [];
    }

    public function getPostById(int $id): array
    {
        return [];
    }

    public function getHighlight(array $options = []): array
    {
        $highlights = [];
        $useOptions = array_merge($this->options, $options);

        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey('posts', $useOptions);
        }

        $response = $this->client->request('GET', 'posts', ['query' => $useOptions]);

        // construct blog post
        foreach ($response->toArray() as $post) {

        }

        return $highlights;
    }

    private function getCacheKey($url, $options)
    {
        return sha1($url . serialize($options));
    }
}