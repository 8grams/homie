<?php

namespace App\Libs\Models;

class BlogCard
{
    private $title;
    private $category;
    private $content;
    private $author;
    private $date;
    private $url;
    private $image;
    private $slug;
    private $relatedPosts = [];

    public function __construct(
        $title,
        $category,
        $content,
        $author,
        $date,
        $url,
        $image,
        $slug,
        $relatedPosts = []
    ) {
        $this->title = $title;
        $this->category = $category;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        $this->url = $url;
        $this->image = $image;
        $this->slug = $slug;
        $this->relatedPosts = $relatedPosts;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setRelatedPosts(array $relatedPosts): void
    {
        $this->relatedPosts = $relatedPosts;
    }

    public function getRelatedPosts(): array
    {
        return $this->relatedPosts;
    }
}