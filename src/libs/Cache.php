<?php

namespace App\Libs;

use App\Libs\Interfaces\CacheInterface;
use App\Libs\Interfaces\DataStoreInterface;

class Cache implements CacheInterface
{
    private DataStoreInterface $db;

    public function __construct(DataStoreInterface $db)
    {
        $this->db = $db;
    }

    public function set(string $key, $value, $ttl = null): bool
    {
        $rb = $this->getRb();

        // check if key exists
        $query = $rb->query('SELECT * FROM cache WHERE key = "'.$key.'"');
        $result = $query->fetch();
        if ($result) {
            $rb->exec('UPDATE cache SET value = "'.$value.'", created_time = "'.time().'", expired_time = "'.time() + $ttl.'" WHERE key = "'.$key);
            return true;
        }

        $rb->exec('INSERT INTO cache (key, value, created_time, expired_time) VALUES ("'.$key.'", "'.$value.'", "'.time().'", "'.time() + $ttl.'")');
        return true;
    }

    public function get(string $key): mixed
    {
        $rb = $this->getRb();
        $query = $rb->query('SELECT value, expired_time FROM cache WHERE key = "'.$key.'" and expired_time > "'.time().'"');

        // return null if no data
        if (!$query) {
            return null;
        }
        $result = $query->fetch();
        return $result['value'];
    }

    public function delete(string $key): bool
    {
        $rb = $this->getRb();
        $rb->exec('DELETE FROM cache WHERE key = "'.$key.'"');
        return true;
    }

    public function flush(): bool
    {
        $rb = $this->getRb();
        $rb->exec('DELETE FROM cache');
        return true;
    }

    private function getRb()
    {
        return $this->db->getPDO();
    }
}