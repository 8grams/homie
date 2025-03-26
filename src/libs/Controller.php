<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    public function render(Request $request): Response
    {
        if (strpos($request->getPathInfo(), '/data/assets/') === 0) {
            $response = new BinaryFileResponse('..' /* getcwd() === './public' */ . $request->getPathInfo());
            $response->headers->set('Content-Type', $response->getFile()->getMimeType());
            return $response;
        }
        return $this->renderPage($request);
    }

    public function renderPage(Request $request): Response
    {
        $path = $request->attributes->get('path');
        if (!$path || empty($path)) {
            $path = 'index';
        }

        if ($path === 'admin') {
            return new RedirectResponse('admin/home');
        }

        $view = $this->viewEngine->make($path);
        $view->setDependencies(
            $this->request,
            $this->cache,
            $this->db,
            $this->blog,
            $this->mailer,
            $this->config
        );

        // set default layouts, navbar, and footer
        $view->setDefaultLayouts();

        $response = $view->render();
        return new Response($response);
    }
}
