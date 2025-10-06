<?php

namespace StaticService\S3;

use StaticService\Interface\S3\Connection;
use StaticService\Interface\S3\S3ServiceInterface;
use Symfony\Component\Uid\Uuid;

class S3Service implements S3ServiceInterface
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

        return $object->get('Body') ?? '';
    }

    public function add(string $path): string
    {
        $key = $this->generateKey();
        $fstream = fopen($path, 'r');
        $this->connection->getClient()->putObject(
            [
                'Bucket' => $this->bucket,
                'Key' => (string) $key,
                'Body' => $fstream,
            ]
        );

        return $key;
    }

    private function generateKey(): string
    {
        return Uuid::v4();
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
