<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    public function render(Request $request): Response
    {
        try {
            return $this->renderPage($request);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return new Response($e->getMessage(), 500);
        }
        
    }

    public function renderPage(Request $request): Response
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