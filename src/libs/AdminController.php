<?php

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseController
{
    public function render(Request $request): Response
    {
        extract($request->attributes->all(), EXTR_SKIP);
        ob_start();
        include sprintf(__DIR__.'/../admin/%s.php', $request->attributes->get('path'));
        return new Response(ob_get_clean());
    }
}