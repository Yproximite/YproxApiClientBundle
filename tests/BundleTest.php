<?php

namespace Yproximite\Bundle\YproxApiClientBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yproximite\Api\Client\Client;
use Yproximite\Api\Service\ServiceAggregator;

class BundleTest extends TestCase
{
    public function testBundle(): void
    {
        $kernel = new YproxApiClientBundleTestKernel();
        $kernel->boot();

        $container = $kernel->getContainer()->get('test.service_container');
        static::assertInstanceOf(ContainerInterface::class, $container);

        static::assertInstanceOf(DummyHttpClient::class, $container->get('yprox_api_client.http_client'));
        static::assertInstanceOf(Client::class, $container->get('yprox_api_client.client.default'));
        static::assertInstanceOf(ServiceAggregator::class, $container->get('yprox_api_client.service_aggregator.default'));
    }
}
