<?php

namespace App\Libs;

use League\Plates\Engine;

class ViewEngine extends Engine
{
    public function __construct($directory = null, $fileExtension = 'php')
    {
        parent::__construct($directory, $fileExtension);
    }

    public function make($name, array $data = array()): ViewTemplate
    {
        $template = new ViewTemplate($this, $name);
        $template->data($data);
        return $template;
    }

    public function render($name, array $data = array()): string
    {
        return $this->make($name)->render($data);
    }
}