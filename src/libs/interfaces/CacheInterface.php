<?php

namespace App\Libs\Interfaces;

interface CacheInterface {
    public function set(string $key, $value, $ttl = null): bool;
    public function get(string $key): mixed;
    public function delete(string $key): bool;
    public function flush(): bool;
}
