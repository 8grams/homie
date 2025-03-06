<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    public function render(Request $request): Response
    {
        $path = $request->attributes->get('path');
        if (!$path || empty($path)) {
            $path = 'index';
        }

        $view = $this->viewEngine->make($path);
        $view->setDependencies(
            $this->request, 
            $this->cache, 
            $this->db, 
            $this->client,
            $this->config
        );

        // set default layouts, navbar, and footer
        $view->setDefaultLayouts();

        $response = $view->render();
        return new Response($response);
    }
}