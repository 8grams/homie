<?php

namespace App\Libs\Interfaces;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Libs\Interfaces\DataStoreInterface;
use App\Libs\Interfaces\BlogInterface;
use App\Libs\Interfaces\CacheInterface;
use App\Libs\Auth\Authenticator;
use App\Libs\Mailer;
use App\Libs\ViewEngine;

/**
 * Base controller class that provides common functionality for all controllers
 * 
 * This class provides:
 * - Dependency injection for common services
 * - Request handling
 * - Caching
 * - Database access
 * - Blog functionality
 * - View rendering
 * - Authentication
 * - Email sending
 * - Configuration access
 */
class BaseController
{
    /** @var string Current locale */
    protected $locale;

    /** @var Request HTTP request object */
    protected Request $request;

    /** @var CacheInterface Cache service */
    protected CacheInterface $cache;

    /** @var DataStoreInterface Database service */
    protected DataStoreInterface $db;

    /** @var BlogInterface Blog service */
    protected BlogInterface $blog;

    /** @var ViewEngine Template engine */
    protected ViewEngine $viewEngine;

    /** @var Authenticator Authentication service */
    protected Authenticator $authenticator;

    /** @var Mailer Email service */
    protected Mailer $mailer;

    /** @var array Configuration array */
    protected $config;
    
    /**
     * Set all required dependencies for the controller
     * 
     * @param Request $request HTTP request object
     * @param CacheInterface $cache Cache service
     * @param DataStoreInterface $db Database service
     * @param BlogInterface $blog Blog service
     * @param ViewEngine $viewEngine Template engine
     * @param Authenticator $authenticator Authentication service
     * @param Mailer $mailer Email service
     * @param array $config Configuration array
     */
    public function setDependencies(
        Request $request, 
        CacheInterface $cache, 
        DataStoreInterface $db, 
        BlogInterface $blog,
        ViewEngine $viewEngine,
        Authenticator $authenticator,
        Mailer $mailer,
        $config = []
        )
    {
        $this->request = $request;
        $this->cache = $cache;
        $this->db = $db;
        $this->blog = $blog;
        $this->viewEngine = $viewEngine;
        $this->authenticator = $authenticator;
        $this->mailer = $mailer;
        $this->config = $config;
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
}