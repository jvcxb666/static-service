<?php

namespace StaticService\S3;

use StaticService\Interface\Cache\CacheServiceInterface;
use StaticService\Interface\S3\CachedS3ServiceInterface;
use StaticService\Interface\S3\S3ServiceInterface;

class CachedS3Service implements CachedS3ServiceInterface
{
    public function __construct(
        private readonly S3ServiceInterface $s3,
        private readonly CacheServiceInterface $cache,
    ) {
    }

    public function get(string $key): string
    {
        $value = $this->cache->get($this->createKey($key));
        if (null === $value) {
            $value = $this->s3->get($key);
            $this->save($key, $value);
        }

        return $value;
    }

    public function add(string $path): string
    {
        return $this->s3->add($path);
    }

    private function createKey(string $key): string
    {
        return self::KEY_PREFIX.$key;
    }

    private function save(string $key, string $value): void
    {
        if ('' !== $value) {
            $this->cache->set($this->createKey($key), $value);
        }
    }
}
