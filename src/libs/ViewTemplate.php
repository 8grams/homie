<?php

namespace App\Libs;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use League\Plates\Template\Template;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Auth\Authenticator;
use App\Libs\Interfaces\BlogInterface;
use App\Libs\ViewEngine;
use App\Libs\Mailer;

/**
 * Extended template class that adds additional functionality to Plates
 * 
 * This class provides:
 * - Dependency injection for various services
 * - Layout management
 * - Component loading
 * - Translation support
 * - Asset management
 */
class ViewTemplate extends Template 
{
    protected Request $request; 
    protected CacheInterface $cache;
    protected DataStoreInterface $db; 
    protected BlogInterface $blog;
    protected Authenticator $authenticator;
    protected Mailer $mailer;
    protected $config;
    protected $locale;
    protected $slug;
    protected $langs = [];

    /**
     * Set all required dependencies for the template
     * 
     * @param Request $request The HTTP request object
     * @param CacheInterface $cache Cache service
     * @param DataStoreInterface $db Database service
     * @param BlogInterface $blog Blog service
     * @param Mailer $mailer Mailer service
     * @param array $config Configuration array
     */
    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        BlogInterface $blog,
        Mailer $mailer,
        $config = []
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->blog = $blog;
        $this->config = $config;
        $this->mailer = $mailer;
        $this->locale = $this->request->attributes->get('locale', $this->config['lang']['default']);

        // set langs by loading from lang files
        $this->langs = $this->loadLangs();
    }

    /**
     * Load language translations from JSON file
     * 
     * @return array Array of translations
     */
    private function loadLangs()
    {
        $jsonFilePath = $this->config['lang']['path'] . '/' . $this->locale . '.json';
        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            return json_decode($jsonContent, true);
        }

        return [];
    }

    /**
     * Set admin-specific dependencies
     * 
     * @param Authenticator $authenticator Authentication service
     */
    public function setAdminDependencies(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * Constructor
     * 
     * @param ViewEngine $engine The template engine
     * @param string $name Template name
     */
    public function __construct(ViewEngine $engine, $name)
    {
        parent::__construct($engine, $name);
    }

    /**
     * Load and render a component from the pages directory
     * 
     * @param string $name Component name
     */
    public function loadComponent($name)
    {
        $this->start($name);
        include sprintf(__DIR__ . "/../pages/components/%s.php", $name);
        $this->stop();

        echo $this->section($name);
    }

    /**
     * Load and render a component from the admin directory
     * 
     * @param string $name Component name
     */
    public function loadAdminComponent($name)
    {
        $this->start($name);
        include sprintf(__DIR__ . "/../internal/admin/components/%s.php", $name);
        $this->stop();

        echo $this->section($name);
    }

    /**
     * Set up default layouts for email templates
     */
    public function setEmailDefaultLayouts()
    {
        $this->layout('layouts/main');
        
        $this->start('header');
        include __DIR__ . "/../internal/emails/layouts/header.php";
        $this->stop();

        $this->start('footer');
        include __DIR__ . "/../internal/email/layouts/footer.php";
        $this->stop();
    }

    /**
     * Set up default layouts for admin pages
     */
    public function setAdminDefaultLayouts()
    {
        $this->layout('layouts/main');

        $this->start('navbar');
        include __DIR__ . "/../internal/admin/layouts/navbar.php";
        $this->stop();

        $this->start('sidebar');
        include __DIR__ . "/../internal/admin/layouts/sidebar.php";
        $this->stop();

        $this->start('footer');
        include __DIR__ . "/../internal/admin/layouts/footer.php";
        $this->stop();
    }
    
    /**
     * Set up default layouts for public pages
     */
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

    /**
     * Set up layouts for sitemap pages
     */
    public function setSitemapLayouts()
    {
        $this->layout('layout');
    }

    /**
     * Get query parameters from the request
     * 
     * @param string $name Optional parameter name to get
     * @return mixed Query parameter value or all parameters
     */
    public function getQueryParams($name = "")
    {
        if ($name) {
            return $this->request->query->get($name);
        }

        return $this->request->query->all();
    }

    /**
     * Get JSON payload from the request
     * 
     * @param string $name Optional parameter name to get
     * @return mixed JSON payload value or all payload
     */
    public function getJsonPayload($name = "")
    {
        if ($name) {
            return $this->request->getPayload()->get($name);
        }

        return $this->request->getPayload()->all();
    }

    /**
     * Get form data from the request
     * 
     * @param string $name Optional parameter name to get
     * @return mixed Form data value or all form data
     */
    public function getFormData($name = "")
    {
        if ($name) {
            return $this->request->request->get($name);
        }

        return $this->request->request->all();
    }

    /**
     * Get the session from the request
     * 
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSession()
    {
        return $this->request->getSession();
    }

    /**
     * Get translation for a label
     * 
     * @param string $label Translation key
     * @param string|null $default Default value if translation not found
     * @return string Translated text
     */
    public function trans($label, $default=null)
    {
        // base64 encode of the current url
        $urlHash = base64_encode(str_replace("/" . $this->locale, "", $this->request->getUri()));
        $trans = $this->db->init()->find('translations', [], 'label = ? AND locale = ? AND url_hash = ?', [$label, $this->locale, $urlHash]);
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

    /**
     * Get asset URL by key
     * 
     * @param string $key Asset key
     * @param string $default Default URL if asset not found
     * @return string Asset URL
     */
    public function asset($key, $default)
    {
        // base64 encode of the current url
        $urlHash = base64_encode(str_replace("/" . $this->locale, "", $this->request->getUri()));
        $assets = $this->db->init()->find('assets', [], 'key = ? AND url_hash = ?', [$key, $urlHash]);
        if (count($assets) > 0) {
            $asset = array_pop($assets);
            return $asset->src;
        }
        
        return $default;
    }

    public function link($link, $default)
    {
        // base64 encode of the current url
        $urlHash = base64_encode(str_replace("/" . $this->locale, "", $this->request->getUri()));
        $links = $this->db->init()->find('links', [], 'link = ? AND locale = ? AND url_hash = ?', [$link, $this->locale, $urlHash]);
        if (count($links) > 0) {
            $link = array_pop($links);
            return $link->value;
        }
        
        return $default;
    }

    /**
     * Create a redirect response
     * 
     * @param string $url URL to redirect to
     * @return RedirectResponse
     */
    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    /**
     * Render the template with data
     * 
     * @param array $data Data to pass to the template
     * @return string Rendered template
     */
    public function render(array $data = array())
    {
        $file = basename($this->name->getFile());
        // check if this empty file
        if ($file == ".php")  {
            $this->name->setName($this->name->getName() . "/index");
            return parent::render($data);
        }

        $this->slug = basename($this->name->getName());

        if (!file_exists($this->name->getPath())) {
            $currentName = clone $this->name;
            if (!str_ends_with($this->name->getName(), "/")) {
                $rpath = clone $this->name;
                $this->name->setName($this->name->getName() . "/index");
                if (file_exists($this->name->getPath())) {
                    return parent::render($data);
                }
            }

            $this->name = $currentName;
            $this->name->setName(dirname($this->name->getName()) . "/slug");

            if (!file_exists($this->name->getPath())) {
                // check index.php
                $this->name->setName(dirname($this->name->getName()) . "/index");
                if (!file_exists($this->name->getPath())) {
                    throw new \Exception("Template file not found");
                } else {
                    if (isset($rpath)) {
                        return new RedirectResponse($rpath->getName() . '/');
                    }
                }
            }
        }
        return parent::render($data);
    }
}