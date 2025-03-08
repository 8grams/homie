<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseController
{
    public function render(Request $request): Response
    {
        session_start();

        $path = $request->attributes->get('path');
        if (!$path || empty($path)) {
            $path = 'index';
        }

        $view = $this->viewEngine->setDirectory($this->config['admin_template']['path'])->make($path);
        $view->setDependencies(
            $this->request, 
            $this->cache, 
            $this->db, 
            $this->client,
            $this->config
        );
        
        $view->setAdminDependencies($this->authenticator);
        
        // set default layouts, navbar, and footer
        $view->setAdminDefaultLayouts();

        $path = $request->attributes->get('path');

        // if user logout, unathenticate user and redirect to login page
        if ($path === 'logout') {
            $this->authenticator->unAuthenticated();
            return new RedirectResponse('login');
        }
        
        // handle unauthenticated user
        if (!$this->authenticator->isAuthenticated()) {
            // if user try to access login page, render login page
            if ($path === 'login') {
                $response = $view->render();
                return new Response($response);
            }
            return new RedirectResponse('login');
        } else { // handle authenticated user
            // if user try to access login page, redirect to home page
            if ($path === 'login') {
                return new RedirectResponse('home');
            }

            if ($path == 'adminer') {
                include_once __DIR__ . '/../adminer/init.php';
                return new Response();
            }

            $response = $view->render();
            return new Response($response);
        }
    }
}