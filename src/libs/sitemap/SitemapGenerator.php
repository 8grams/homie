<?php

namespace App\Libs\Sitemap;

use Closure;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use App\Libs\Sitemap\Crawler\Observer;
use App\Libs\Sitemap\Tags\Url;
use App\Libs\ViewEngine;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class SitemapGenerator
{
    protected Collection $sitemaps;
    protected Uri $urlToBeCrawled;
    protected Crawler $crawler;
    protected $shouldCrawl;
    protected $hasCrawled;
    protected int $concurrency = 10;
    protected bool | int $maximumTagsPerSitemap = false;
    protected ?int $maximumCrawlCount = null;
    protected $config;

    public function __construct(Crawler $crawler, array $config)
    {
        $this->crawler = $crawler;
        $this->config = $config;
        $this->sitemaps = new Collection([new Sitemap]);
        $this->hasCrawled = fn (Url $url, ?ResponseInterface $response = null) => $url;
    }

    public function configureCrawler(Closure $closure): static
    {
        call_user_func_array($closure, [$this->crawler]);
        return $this;
    }

    public function setConcurrency(int $concurrency): static
    {
        $this->concurrency = $concurrency;
        return $this;
    }

    public function setMaximumCrawlCount(int $maximumCrawlCount): static
    {
        $this->maximumCrawlCount = $maximumCrawlCount;
        return $this;
    }

    public function maxTagsPerSitemap(int $maximumTagsPerSitemap = 50000): static
    {
        $this->maximumTagsPerSitemap = $maximumTagsPerSitemap;
        return $this;
    }

    public function setUrl(string $urlToBeCrawled): static
    {
        $this->urlToBeCrawled = new Uri($urlToBeCrawled);
        if ($this->urlToBeCrawled->getPath() === '') {
            $this->urlToBeCrawled = $this->urlToBeCrawled->withPath('/');
        }

        return $this;
    }

    public function shouldCrawl(callable $shouldCrawl): static
    {
        $this->shouldCrawl = $shouldCrawl;
        return $this;
    }

    public function hasCrawled(callable $hasCrawled): static
    {
        $this->hasCrawled = $hasCrawled;
        return $this;
    }

    public function getSitemap(): Sitemap
    {
        if ($this->config['execute_javascript']) {
            $this->crawler->executeJavaScript();
        }

        if ($this->config['chrome_binary_path']) {
            $this->crawler
                ->setBrowsershot((new Browsershot)->setChromePath($this->config['chrome_binary_path']))
                ->acceptNofollowLinks();
        }

        if (! is_null($this->maximumCrawlCount)) {
            $this->crawler->setTotalCrawlLimit($this->maximumCrawlCount);
        }

        $this->crawler
            ->setCrawlProfile($this->getCrawlProfile())
            ->addCrawlObserver($this->getCrawlObserver())
            ->setConcurrency($this->concurrency)
            ->startCrawling($this->urlToBeCrawled);

        return $this->sitemaps->first();
    }

    public function writeToFile(string $path, ViewEngine $viewEngine, array $config): static
    {
        $sitemap = $this->getSitemap();
        if ($this->maximumTagsPerSitemap) {
            $sitemap = SitemapIndex::create();
            $format = str_replace('.xml', '_%d.xml', $path);

            // Parses each sub-sitemaps, writes and push them into the sitemap index
            $this->sitemaps->each(function (Sitemap $item, int $key) use ($sitemap, $format, $viewEngine, $config) {
                $path = sprintf($format, $key);

                $item->writeToFile(sprintf($format, $key), $viewEngine, $config);
                $sitemap->add($this->config['site_url'] . last(explode('public', $path)));
            });
        }

        $sitemap->writeToFile($path, $viewEngine, $config);
        return $this;
    }

    protected function getCrawlProfile(): CrawlProfile
    {
        $shouldCrawl = function (UriInterface $url) {
            if ($url->getHost() !== $this->urlToBeCrawled->getHost()) {
                return false;
            }

            if (! is_callable($this->shouldCrawl)) {
                return true;
            }

            return ($this->shouldCrawl)($url);
        };

        $profileClass = $this->config['crawl_profile'];
        $profile = new $profileClass($this->urlToBeCrawled);

        if (method_exists($profile, 'shouldCrawlCallback')) {
            $profile->shouldCrawlCallback($shouldCrawl);
        }

        return $profile;
    }

    protected function getCrawlObserver(): CrawlObserver
    {
        $performAfterUrlHasBeenCrawled = function (UriInterface $crawlerUrl, ?ResponseInterface $response = null) {
            $sitemapUrl = ($this->hasCrawled)(Url::create((string) $crawlerUrl), $response);

            if ($this->shouldStartNewSitemapFile()) {
                $this->sitemaps->push(new Sitemap);
            }

            if ($sitemapUrl) {
                $this->sitemaps->last()->add($sitemapUrl);
            }
        };

        return new Observer($performAfterUrlHasBeenCrawled);
    }

    protected function shouldStartNewSitemapFile(): bool
    {
        if (! $this->maximumTagsPerSitemap) {
            return false;
        }

        $currentNumberOfTags = count($this->sitemaps->last()->getTags());
        return $currentNumberOfTags >= $this->maximumTagsPerSitemap;
    }
}
