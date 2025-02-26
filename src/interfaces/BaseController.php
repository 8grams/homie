<?php

namespace App\Interfaces;

use App\Interfaces\DataStoreInterface;
use App\Interfaces\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class BaseController
{
    protected CacheInterface $cache;
    protected DataStoreInterface $db;
    protected HttpClientInterface $client;
    protected Request $request;

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        HttpClientInterface $client)
    {
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->request = $request;
    }
}