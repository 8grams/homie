<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    public function render(Request $request): Response
    {
        parent::render($request);

        $view = $this->viewEngine->make($request->attributes->get('path'));
        $view->setDependencies(
            $this->request, 
            $this->cache, 
            $this->db, 
            $this->client, 
            $this->locale
        );

        // set default layouts, navbar, and footer
        $view->setDefaultLayouts();

        $response = $view->render();
        return new Response($response);
    }
}