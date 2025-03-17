<?php

namespace App\Libs\Models\Blog;

class Category
{
    private $id;
    private $name;
    private $slug;

    public function __construct($id, $name, $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}