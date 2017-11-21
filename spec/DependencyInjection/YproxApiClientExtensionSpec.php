<?php

namespace spec\Yproximite\Bundle\YproxApiClientBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Yproximite\Api\Client\Client;
use Yproximite\Api\Service\ServiceAggregator;
use Yproximite\Bundle\YproxApiClientBundle\DependencyInjection\YproxApiClientExtension;

class YproxApiClientExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(YproxApiClientExtension::class);
    }

    function it_should_load(ContainerBuilder $container)
    {
        $configs = [
            'ypox_api_client' => [
                'http_client' => 'httplug.client.guzzle6',
                'clients'     => [
                    'default' => [
                        'api_key' => '12345',
                    ],
                    'custom'  => [
                        'api_key'  => '67890',
                        'base_url' => 'http://api.host.com',
                    ],
                ],
            ],
        ];

        $container->setAlias('yprox_api_client.http_client', 'httplug.client.guzzle6')->shouldBeCalled();

        // "default" client
        $client = new Definition(Client::class, [new Reference('yprox_api_client.http_client'), '12345']);
        $client->setPublic(false);

        $container->setDefinition('yprox_api_client.client.default', $client)->shouldBeCalled();

        $clientRef  = new Reference('yprox_api_client.client.default');
        $aggregator = new Definition(ServiceAggregator::class, [$clientRef]);
        $aggregator->setPublic(true);

        $container->setDefinition('yprox_api_client.service_aggregator.default', $aggregator)->shouldBeCalled();

        // "custom" client
        $client = new Definition(Client::class, [new Reference('yprox_api_client.http_client'), '67890', 'http://api.host.com']);
        $client->setPublic(false);

        $container->setDefinition('yprox_api_client.client.custom', $client)->shouldBeCalled();

        $clientRef  = new Reference('yprox_api_client.client.custom');
        $aggregator = new Definition(ServiceAggregator::class, [$clientRef]);
        $aggregator->setPublic(true);

        $container->setDefinition('yprox_api_client.service_aggregator.custom', $aggregator)->shouldBeCalled();

        $this->load($configs, $container);
    }
}
