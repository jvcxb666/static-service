<?php

namespace StaticService\Cache;

use Predis\Client;
use Predis\ClientInterface;
use StaticService\Interface\Cache\Connection;

class RedisConnection implements Connection
{
    private ClientInterface $client;

    public function __construct(
        string $connectionString,
    ) {
        $this->connect($connectionString);
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    private function connect(string $connectionString): void
    {
        $this->client = new Client($connectionString);
    }
}
