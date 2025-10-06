<?php

namespace StaticService\Cache;

use StaticService\Interface\Cache\CacheServiceInterface;
use StaticService\Interface\Cache\Connection;

class RedisCacheService implements CacheServiceInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function get(string $key): ?string
    {
        return $this->connection->getClient()->get($key);
    }

    public function set(string $key, string $value): void
    {
        $this->connection->getClient()->set($key, $value);
    }
}
