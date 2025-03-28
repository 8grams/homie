<?php

namespace App\Libs;

use App\Libs\Interfaces\CacheInterface;
use App\Libs\Interfaces\DataStoreInterface;

/**
 * Database-backed cache implementation
 * 
 * This class provides caching functionality using a database as storage,
 * implementing the CacheInterface for consistent cache operations.
 */
class Cache implements CacheInterface
{
    /** @var DataStoreInterface Database service for cache storage */
    private DataStoreInterface $db;

    /**
     * Constructor
     * 
     * @param DataStoreInterface $db Database service for cache storage
     */
    public function __construct(DataStoreInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Set a value in the cache with optional TTL
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache (can be array, string, or other serializable data)
     * @param int|null $ttl Time to live in seconds
     * @return bool Success status
     */
    public function set(string $key, $value, $ttl = null): bool
    {
        $rb = $this->getRb();
        
        // Serialize the value if it's not a string
        $serializedValue = is_string($value) ? $value : serialize($value);

        // check if key exists
        $query = $rb->query('SELECT * FROM cache WHERE key = "'.$key.'"');
        $result = $query->fetch();
        if ($result) {
            $rb->exec('UPDATE cache SET value = "'.$serializedValue.'", created_time = "'.time().'", expired_time = "'.time() + $ttl.'" WHERE key = "'.$key);
            return true;
        }

        $rb->exec('INSERT INTO cache (key, value, created_time, expired_time) VALUES ("'.$key.'", "'.$serializedValue.'", "'.time().'", "'.time() + $ttl.'")');

        $this->deleteExpired();
        return true;
    }

    /**
     * Get a value from the cache
     * 
     * @param string $key Cache key
     * @return mixed Cached value or null if not found/expired
     */
    public function get(string $key): mixed
    {
        $rb = $this->getRb();
        $query = $rb->query('SELECT value, expired_time FROM cache WHERE key = "'.$key.'" and expired_time > "'.time().'"');

        // return null if no data
        if (!$query) {
            return null;
        }
        $result = $query->fetch();
        
        // Try to unserialize the value
        $value = $result['value'];
        $unserialized = @unserialize($value);
        
        // Return unserialized value if successful, otherwise return original string
        return $unserialized !== false ? $unserialized : $value;
    }

    /**
     * Delete a value from the cache
     * 
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete(string $key): bool
    {
        $rb = $this->getRb();
        $rb->exec('DELETE FROM cache WHERE key = "'.$key.'"');
        return true;
    }

    /**
     * Delete expired cache entries
     * 
     * @return bool Success status
     */
    public function deleteExpired(): bool
    {
        $rb = $this->getRb();
        $rb->exec('DELETE FROM cache WHERE expired_time < "'.time().'"');
        return true;
    }

    /**
     * Clear all values from the cache
     * 
     * @return bool Success status
     */
    public function flush(): bool
    {
        $rb = $this->getRb();
        $rb->exec('DELETE FROM cache');
        return true;
    }

    /**
     * Get the database connection
     * 
     * @return \PDO Database connection
     */
    private function getRb()
    {
        return $this->db->getPDO();
    }
}