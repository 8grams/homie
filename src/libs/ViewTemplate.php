<?php

namespace App\Libs;

use League\Plates\Template\Template;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\HttpClientInterface;
use App\Libs\ViewEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class ViewTemplate extends Template 
{
    protected Request $request; 
    protected CacheInterface $cache;
    protected DataStoreInterface $db; 
    protected HttpClientInterface $client;

    public function setDependencies(Request $request, CacheInterface $cache, DataStoreInterface $db, HttpClientInterface $client)
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
    }

    public function __construct(ViewEngine $engine, $name)
    {
        parent::__construct($engine, $name);
    }

    public function loadComponent($name)
    {
        $this->start($name);
        include sprintf(__DIR__ . "/../pages/components/%s.php", $name);
        $this->stop();

        echo $this->section($name);
    }

    public function setDefaultLayouts()
    {
        $this->layout('layouts/main');

        $this->start('navbar');
        include __DIR__ . "/../pages/layouts/navbar.php";
        $this->stop();

        $this->start('footer');
        include __DIR__ . "/../pages/layouts/footer.php";
        $this->stop();
    }
}