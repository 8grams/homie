<?php

namespace App\Interfaces;

use App\Interfaces\DataStoreInterface;
use App\Interfaces\HttpClientInterface;
use App\Libs\ViewEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class BaseController
{
    protected Request $request;
    protected CacheInterface $cache;
    protected DataStoreInterface $db;
    protected HttpClientInterface $client;
    protected ViewEngine $viewEngine;

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        HttpClientInterface $client,
        ViewEngine $viewEngine,
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->viewEngine = $viewEngine;
        
    }
}