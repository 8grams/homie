<?php

namespace App\Libs;

use League\Plates\Engine;

/**
 * Extended Plates Engine class that provides custom template functionality
 * 
 * This class extends the Plates Engine to provide:
 * - Custom template creation with ViewTemplate
 * - Direct template rendering
 */
class ViewEngine extends Engine
{
    /**
     * Constructor
     * 
     * @param string|null $directory Base directory for templates
     * @param string $fileExtension File extension for template files
     */
    public function __construct($directory = null, $fileExtension = 'php')
    {
        parent::__construct($directory, $fileExtension);
    }

    /**
     * Create a new ViewTemplate instance
     * 
     * @param string $name Template name
     * @param array $data Data to pass to the template
     * @return ViewTemplate
     */
    public function make($name, array $data = array()): ViewTemplate
    {
        $template = new ViewTemplate($this, $name);
        $template->data($data);
        return $template;
    }

    /**
     * Render a template directly
     * 
     * @param string $name Template name
     * @param array $data Data to pass to the template
     * @return string Rendered template content
     */
    public function render($name, array $data = array()): string
    {
        return $this->make($name)->render($data);
    }
}