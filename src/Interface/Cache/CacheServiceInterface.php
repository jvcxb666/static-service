<?php

namespace StaticService\Interface\Cache;

interface CacheServiceInterface
{
    public function get(string $key): ?string;

    public function set(string $key, string $value): void;
}
