<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseController
{
    public function render(Request $request): Response
    {
        $view = $this->viewEngine->make($request->attributes->get('path'));
        $view->setDependencies(
            $this->request, 
            $this->cache, 
            $this->db, 
            $this->client,
            $this->config
        );

        // set default layouts, navbar, and footer
        $view->setAdminDefaultLayouts();

        $response = $view->render();
        return new Response($response);
    }
}