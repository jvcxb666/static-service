<?php

namespace StaticService\S3;

use StaticService\Interface\Cache\CacheServiceInterface;
use StaticService\Interface\S3\S3Provider;
use StaticService\Interface\S3\S3ProviderCached;

class S3FileProviderCached implements S3ProviderCached
{
    public function __construct(
        private readonly S3Provider $s3,
        private readonly CacheServiceInterface $cache,
    ) {
    }

    public function get(string $key): string
    {
        $value = $this->cache->get($this->createKey($key));
        if (null === $value) {
            $value = $this->s3->get($key);
            $this->saveCache($key, $value);
        }

        return $value;
    }

    private function createKey(string $key): string
    {
        return self::KEY_PREFIX.$key;
    }

    private function saveCache(string $key, string $value): void
    {
        if ('' !== $value) {
            $this->cache->set($this->createKey($key), $value);
        }
    }
}
