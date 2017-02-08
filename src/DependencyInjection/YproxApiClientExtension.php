<?php
declare(strict_types=1);

namespace Yproximite\Bundle\YproxApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Yproximite\Api\Client\Client;
use Yproximite\Api\Service\ServiceAggregator;

/**
 * Class YproxApiClientExtension
 */
class YproxApiClientExtension extends Extension
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $container->setAlias('yprox_api_client.http_client', $config['http_client']);

        foreach ($config['clients'] as $clientName => $clientConfig) {
            $this->addClient($clientName, $clientConfig);
            $this->addServiceAggregator($clientName);
        }
    }

    /**
     * @param string $name
     * @param array  $config
     */
    private function addClient(string $name, array $config)
    {
        $arguments = [
            new Reference('yprox_api_client.http_client'),
            $config['api_key'],
        ];

        if (array_key_exists('base_url', $config)) {
            $arguments[] = $config['base_url'];
        }

        $client = new Definition(Client::class, $arguments);
        $client->setPublic(false);

        $this->container->setDefinition(sprintf('yprox_api_client.client.%s', $name), $client);
    }

    /**
     * @param string $name
     */
    private function addServiceAggregator(string $name)
    {
        $clientRef  = new Reference(sprintf('yprox_api_client.client.%s', $name));
        $aggregator = new Definition(ServiceAggregator::class, [$clientRef]);

        $this->container->setDefinition(sprintf('yprox_api_client.service_aggregator.%s', $name), $aggregator);
    }
}
