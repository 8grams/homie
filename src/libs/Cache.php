<?php

namespace App\Libs;

use Symfony\Contracts\Cache\CacheInterface;

class Cache implements CacheInterface
{
    public function get(string $key, callable $callback, ?float $beta = null, ?array &$metadata = null): mixed
    {
        return [];
    }

    public function delete(string $key): bool
    {
        return true;
    }
}