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
        $view = $this->viewEngine->setDirectory($this->config['admin_template']['path'])->make($request->attributes->get('path'));
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
        try {
        if ($this->request->getPathInfo() !== "/admin/login" && !$this->authenticator->isAuthenticated()) {
            
            return new RedirectResponse("/admin/login");
        } else {
            $response = $view->render();
            return new Response($response);
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());die();
    }
    }
}