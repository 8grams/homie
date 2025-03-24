<?php

namespace App\Libs\Sitemap;

use App\Libs\Sitemap\Tags\Sitemap;
use App\Libs\Sitemap\Tags\Tag;
use App\Libs\ViewEngine;

class SitemapIndex
{
    /** @var \App\Libs\Sitemap\Tags\Sitemap[] */
    protected array $tags = [];

    public static function create(): static
    {
        return new static();
    }

    public function add(string | Sitemap $tag): static
    {
        if (is_string($tag)) {
            $tag = Sitemap::create($tag);
        }

        $this->tags[] = $tag;

        return $this;
    }

    public function getSitemap(string $url): ?Sitemap
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'sitemap' && $tag->url === $url;
        });
    }

    public function hasSitemap(string $url): bool
    {
        return (bool) $this->getSitemap($url);
    }

    public function render(ViewEngine $viewEngine): string
    {
        $tags = $this->tags;
        $view = $viewEngine->make('sitemap/sitemapIndex/index', ['tags' => $tags]); 
        $view->setSitemapLayouts();
        return $view->render();
    }

    public function writeToFile(string $path, ViewEngine $viewEngine): static
    {
        file_put_contents($path, $this->render($viewEngine));
        return $this;
    }
}
