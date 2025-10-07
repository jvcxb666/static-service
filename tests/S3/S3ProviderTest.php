<?php

namespace StaticService\Tests\S3;

use Aws\Result;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StaticService\Interface\S3\Connection;
use StaticService\Interface\S3\S3Provider;
use StaticService\S3\S3FileProvider;

class S3ProviderTest extends TestCase
{
    private const BUCKET = 'test';
    private S3Provider $service;
    private Connection&MockObject $connectionMock;
    private S3ClientInterface&MockObject $clientMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->clientMock = $this->createMock(S3Client::class);
        $this->service = new S3FileProvider(
            connection: $this->connectionMock,
            bucket: self::BUCKET,
        );
    }

    public function testGet(): void
    {
        $this->connectionMock
        ->expects($this->once())
        ->method('getClient')
        ->willReturn($this->clientMock);

        $this->clientMock
        ->expects($this->once())
        ->method('__call')
        ->with('getObject')
        ->willReturn(new Result(['Body' => 'value']));

        $result = $this->service->get('key');
        $this->assertSame('value', $result);
    }
}
