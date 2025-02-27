<?php

namespace App\Libs;

use App\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    // default preRender and postRender page
    protected $preRender = [
        'pages/layouts/navbar.php'
    ];

    protected $postRender = [
        'pages/layouts/footer.php'
    ];

    public function render(Request $request): Response
    {
        extract($request->attributes->all(), EXTR_SKIP);
        
        foreach ($this->preRender as $file) {
            include sprintf(__DIR__.'/../%s', $file);
        }

        include sprintf(__DIR__.'/../pages/%s.php', $request->attributes->get('path'));
        
        foreach ($this->postRender as $file) {
            include sprintf(__DIR__.'/../%s', $file);
        }
        
        return new Response($this->template->render('container'));
    }
}