<?php

namespace App\Libs\Interfaces;

use App\Libs\Auth\Authenticator;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\HttpClientInterface;
use App\Libs\ViewEngine;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class BaseController
{
    protected $locale;
    protected Request $request;
    protected CacheInterface $cache;
    protected DataStoreInterface $db;
    protected HttpClientInterface $client;
    protected ViewEngine $viewEngine;
    protected Authenticator $authenticator;
    protected $config;

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        HttpClientInterface $client,
        ViewEngine $viewEngine,
        Authenticator $authenticator,
        $config = []
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->viewEngine = $viewEngine;
        $this->authenticator = $authenticator;
        $this->config = $config;
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }
}