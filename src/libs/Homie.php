<?php

namespace App\Libs;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Homie
{
    static $request;

    public static function init(Request $request)
    {
        Homie::$request = $request;
    }

    public static function locale() {
        return self::$request->attributes->get('locale') ?? 'id';
    }
}