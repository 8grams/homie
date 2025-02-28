<?php

namespace App\Libs\Interfaces;

use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\HttpClientInterface;
use App\Libs\ViewEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;

class BaseController
{
    protected $locale;
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
    
    public function render(Request $request): Response
    {
        $this->locale = $request->attributes->get('locale', 'en');
        return new Response('Hello World');
    }
}