<?php

namespace App\Libs;

use App\Libs\Auth\Authenticator;
use League\Plates\Template\Template;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\HttpClientInterface;
use App\Libs\ViewEngine;
use R;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class ViewTemplate extends Template 
{
    protected Request $request; 
    protected CacheInterface $cache;
    protected DataStoreInterface $db; 
    protected HttpClientInterface $client;
    protected Authenticator $authenticator;
    protected $config;
    protected $locale;

    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        HttpClientInterface $client,
        $config = []
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->client = $client;
        $this->config = $config;
        $this->locale = $this->request->attributes->get('locale', 'en');
    }

    public function setAdminDependencies(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
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

    public function loadAdminComponent($name)
    {
        $this->start($name);
        include sprintf(__DIR__ . "/../admin/components/%s.php", $name);
        $this->stop();

        echo $this->section($name);
    }

    public function setAdminDefaultLayouts()
    {
        $this->layout('layouts/main');

        $this->start('navbar');
        include __DIR__ . "/../admin/layouts/navbar.php";
        $this->stop();

        $this->start('footer');
        include __DIR__ . "/../admin/layouts/footer.php";
        $this->stop();
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

    public function getQueryParams($name = "")
    {
        if ($name) {
            return $this->request->query->get($name);
        }

        return $this->request->query->all();
    }

    public function getJsonPayload($name = "")
    {
        if ($name) {
            return $this->request->getPayload()->get($name);
        }

        return $this->request->getPayload()->all();
    }

    public function getFormData($name = "")
    {
        if ($name) {
            return $this->request->request->get($name);
        }

        return $this->request->request->all();
    }

    public function trans($label, $default)
    {
        $trans = $this->db->init()->find('translation', [], 'label = ? AND locale = ?', [$label, $this->locale]);
        if (count($trans) > 0) {
            $tran = array_pop($trans);
            return $tran->value;
        }
        return $default;
    }

    public function asset($key, $default)
    {
        $assets = $this->db->init()->find('asset', [], 'key = ?', [$key]);
        if (count($assets) > 0) {
            $asset = array_pop($assets);
            return $asset->src;
        }
        return $default;
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }   
}