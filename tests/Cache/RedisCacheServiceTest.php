<?php

namespace Cache;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Predis\ClientInterface;
use StaticService\Cache\RedisCacheService;
use StaticService\Interface\Cache\CacheServiceInterface;
use StaticService\Interface\Cache\Connection;

class RedisCacheServiceTest extends TestCase
{
    private CacheServiceInterface $cache;
    private Connection&MockObject $connectionMock;
    private ClientInterface&MockObject $clientMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->clientMock = $this->createMock(ClientInterface::class);
        $this->cache = new RedisCacheService(
            connection: $this->connectionMock,
        );
    }

    #[DataProvider('getProvider')]
    public function testGet(string $key, ?string $expect): void
    {
        $this->connectionMock
        ->expects($this->once())
        ->method('getClient')
        ->willReturn($this->clientMock);

        $this->clientMock
        ->expects($this->once())
        ->method('__call')
        ->with('get')
        ->willReturn($expect);

        $this->assertSame($expect, $this->cache->get($key));
    }

    public function testSet(): void
    {
        $this->connectionMock
        ->expects($this->once())
        ->method('getClient')
        ->willReturn($this->clientMock);

        $this->clientMock
        ->expects($this->once())
        ->method('__call')
        ->with('set');

        $this->cache->set('testKey', 'testValue');
    }

    public static function getProvider(): array
    {
        return [
            [
                'key' => 'short',
                'expect' => '1',
            ],
            [
                'key' => 'long',
                'expect' => '123456789708124354675869707654312453645876908675423425364789708765432349583495834573485728354724893579823573498xs',
            ],
            [
                'key' => 'empty',
                'expect' => null,
            ],
        ];
    }
}
