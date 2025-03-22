<?php

namespace App\Libs\Sitemap;

use App\Libs\Sitemap\Tags\Tag;
use App\Libs\Sitemap\Tags\Url;

class Sitemap
{
    /** @var \App\Libs\Sitemap\Tags\Url[] */
    protected array $tags = [];

    public static function create(): static
    {
        return new static();
    }

    public function add(string | Url | iterable $tag): static
    {
        if (is_iterable($tag)) {
            foreach ($tag as $item) {
                $this->add($item);
            }

            return $this;
        }

        if (is_string($tag)) {
            $tag = Url::create($tag);
        }

        if (! in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getUrl(string $url): ?Url
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'url' && $tag->url === $url;
        });
    }

    public function hasUrl(string $url): bool
    {
        return (bool) $this->getUrl($url);
    }

    public function render(): string
    {
        $tags = collect($this->tags)->unique('url')->filter();

        return $tags->__toString();
    }

    public function writeToFile(string $path): static
    {
        file_put_contents($path, $this->render());
        return $this;
    }
}
