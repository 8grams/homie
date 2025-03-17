<?php

namespace App\Libs\Models\Blog;

class Author
{
    private $id;
    private $name;
    private $avatar;

    public function __construct($id, $name, $avatar)
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}