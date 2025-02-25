<?php

namespace App\Libs;

use App\Interfaces\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class WordpressHttpClient implements HttpClientInterface
{
    public function __construct(
        private $key,
        private $url,
        private CacheInterface $cache,
        private HttpClientInterface $client,
    ) {
    }

    public function get(array $options = []): array
    {
        return [];
    }
}