<?php

namespace App\Libs;

use App\Interfaces\DataStoreInterface;
use App\Interfaces\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;

class Controller
{
    private CacheInterface $cache;
    private DataStoreInterface $db;
    private HttpClientInterface $client;
    private Request $request;

    public function __construct(Request $request, CacheInterface $cache, DataStoreInterface $db, HttpClientInterface $client)
    {
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->request = $request;
    }

    public function render(Request $request): Response
    {
        extract($request->attributes->all(), EXTR_SKIP);
        ob_start();
        include sprintf(__DIR__.'/../pages/%s.php', $request->attributes->get('path'));
        return new Response(ob_get_clean());
    }

    public static function locale() 
    {
        return self::$request->attributes->get('locale') ?? 'id';
    }
}