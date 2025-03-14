<?php

namespace App\Libs\Interfaces;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Auth\Authenticator;
use App\Libs\ViewEngine;

class BaseController
{
    protected $locale;
    protected Request $request;
    protected CacheInterface $cache;
    protected DataStoreInterface $db;
    protected BlogInterface $blog;
    protected ViewEngine $viewEngine;
    protected Authenticator $authenticator;
    protected $config;

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        BlogInterface $blog,
        ViewEngine $viewEngine,
        Authenticator $authenticator,
        $config = []
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->blog = $blog;
        $this->viewEngine = $viewEngine;
        $this->authenticator = $authenticator;
        $this->config = $config;
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }
}