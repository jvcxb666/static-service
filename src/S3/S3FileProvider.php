<?php

namespace StaticService\S3;

use StaticService\Interface\S3\Connection;
use StaticService\Interface\S3\S3Provider;

class S3FileProvider implements S3Provider
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $bucket,
    ) {
        $this->createBucket();
    }

    public function get(string $key): string
    {
        $object = $this->connection->getClient()->getObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        return $object?->get('Body') ?? '';
    }

    private function createBucket(): void
    {
        try {
            $this->connection->getClient()->getBucketAcl(
                [
                    'Bucket' => $this->bucket,
                ]
            );
        } catch (\Throwable $e) {
            $this->connection->getClient()->createBucket([
                'Bucket' => $this->bucket,
            ]);
        }
    }
}
