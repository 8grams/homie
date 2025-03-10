<?php

namespace App\Libs;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use League\Plates\Template\Template;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\HttpClientInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Auth\Authenticator;
use App\Libs\ViewEngine;

class ViewTemplate extends Template 
{
    protected Request $request; 
    protected CacheInterface $cache;
    protected DataStoreInterface $db; 
    protected HttpClientInterface $client;
    protected Authenticator $authenticator;
    protected $config;
    protected $locale;
    protected $slug;
    protected $langs = [];

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
        $this->locale = $this->request->attributes->get('locale', $this->config['lang']['default']);

        // set langs by loading from lang files
        $this->langs = $this->loadLangs();

    }

    private function loadLangs()
    {
        $jsonFilePath = $this->config['lang']['path'] . '/' . $this->locale . '.json';
        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            return json_decode($jsonContent, true);
        }

        return [];
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

    // https://symfony.com/doc/current/session.html
    public function getSession()
    {
        return $this->request->getSession();
    }

    public function trans($label, $default=null)
    {
        $trans = $this->db->init()->find('translations', [], 'label = ? AND locale = ?', [$label, $this->locale]);
        if (count($trans) > 0) {
            $tran = array_pop($trans);
            return $this->e($tran->value);
        }

        // check on langs
        if (is_null($default)) {
            $default = "";
            if (array_key_exists($label, $this->langs)) {
                $default = $this->langs[$label];
            }
        }
        
        return $this->e($default);
    }

    public function asset($key, $default)
    {
        $assets = $this->db->init()->find('assets', [], 'key = ?', [$key]);
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

    public function render(array $data = array())
    {
        $this->slug = basename($this->name->getName());
        if (!file_exists($this->name->getPath())) {
            $this->name->setName(dirname($this->name->getName()) . "/slug");
            if (!file_exists($this->name->getPath())) {
                // check index.php
                $this->name->setName(dirname($this->name->getName()) . "/index");
                if (!file_exists($this->name->getPath())) {
                    throw new \Exception("Template file not found");
                }
            }   
        }
        return parent::render($data);
    }
}