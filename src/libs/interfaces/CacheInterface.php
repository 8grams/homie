<?php

namespace App\Libs\Interfaces;

/**
 * Interface for caching operations
 * 
 * This interface defines methods for:
 * - Setting cache entries with optional TTL
 * - Retrieving cache entries
 * - Deleting cache entries
 * - Flushing the entire cache
 */
interface CacheInterface {
    /**
     * Set a value in the cache
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int|null $ttl Time to live in seconds
     * @return bool Success status
     */
    public function set(string $key, $value, $ttl = null): bool;

    /**
     * Get a value from the cache
     * 
     * @param string $key Cache key
     * @return mixed Cached value or null if not found
     */
    public function get(string $key): mixed;

    /**
     * Delete a value from the cache
     * 
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete(string $key): bool;

    /**
     * Clear all values from the cache
     * 
     * @return bool Success status
     */
    public function flush(): bool;
}
