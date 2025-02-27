<?php

namespace App\Interfaces;

use App\Interfaces\DataStoreInterface;
use App\Interfaces\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use League\Plates\Engine as TemplateEngine;

class BaseController
{
    protected Request $request;
    protected CacheInterface $cache;
    protected DataStoreInterface $db;
    protected HttpClientInterface $client;
    protected TemplateEngine $template;
    protected $myData = "Data";

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        HttpClientInterface $client,
        TemplateEngine $template,
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->template = $template;
        
    }
}