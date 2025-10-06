<?php

namespace StaticService\S3;

use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use StaticService\Interface\S3\Connection;

class S3Connection implements Connection
{
    private S3Client $client;

    public function __construct(
        string $region,
        string $user,
        string $password,
        string $url,
    ) {
        $this->connect($region, $user, $password, $url);
    }

    public function getClient(): S3ClientInterface
    {
        return $this->client;
    }

    private function connect(
        string $region,
        string $user,
        string $password,
        string $url,
    ): void {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $user,
                'secret' => $password,
            ],
            'endpoint' => $url,
            'use_path_style_endpoint' => true,
        ]);
    }
}
