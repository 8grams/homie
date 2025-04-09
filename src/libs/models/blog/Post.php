<?php

namespace App\Libs\Models\Blog;

use App\Libs\Models\Blog\Author;

class Post
{
    private $id;
    private $title;
    private $categories;
    private $tags = [];
    private $excerpt;
    private $content;
    private $author;
    private $date;
    private $url;
    private $image;
    private $slug;
    private $relatedPosts = [];
    private $metaTitle;
    private $metaDescription;
    private $metaKeywords;
    private $metaImage;
    private $metaUrl;

    public function __construct(
        $id,
        $title,
        $categories,
        $tags,
        $excerpt,
        $content,
        $author,
        $date,
        $url,
        $image,
        $slug,
        $relatedPosts = [],
        $metaTitle = null,
        $metaDescription = null,
        $metaKeywords = null,
        $metaImage = null,
        $metaUrl = null,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->excerpt = $excerpt;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        $this->url = $url;
        $this->image = $image;
        $this->slug = $slug;
        $this->relatedPosts = $relatedPosts;
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->metaKeywords = $metaKeywords;
        $this->metaImage = $metaImage;
        $this->metaUrl = $metaUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): Author
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

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function getMetaImage(): ?string
    {
        return $this->metaImage;
    }

    public function getMetaUrl(): ?string
    {
        return $this->metaUrl;
    }
}