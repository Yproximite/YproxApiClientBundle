<?php

namespace Yproximite\Bundle\YproxApiClientBundle\Tests;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Yproximite\Bundle\YproxApiClientBundle\YproxApiClientBundle;

abstract class AbstractYproxApiClientBundleTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new YproxApiClientBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->setDefinition('dummy_http_client', new Definition(DummyHttpClient::class));

        $containerBuilder->loadFromExtension('framework', [
            'secret' => 'my-secret',
            'test'   => true,
            'router' => [
                'utf8' => true,
            ],
        ]);

        $containerBuilder->loadFromExtension('yprox_api_client', [
            'http_client' => 'dummy_http_client',
            'clients' => [
                'default' => [
                    'api_key' => 'the api key'
                ]
            ]
        ]);
    }
}

if (AbstractYproxApiClientBundleTestKernel::VERSION_ID >= 50100) { // @phpstan-ignore-line
    class YproxApiClientBundleTestKernel extends AbstractYproxApiClientBundleTestKernel
    {
        protected function configureRoutes(RoutingConfigurator $routes): void
        {
        }
    }
} else { // @phpstan-ignore-line
    class YproxApiClientBundleTestKernel extends AbstractYproxApiClientBundleTestKernel
    {
        protected function configureRoutes(RouteCollectionBuilder $routes): void
        {
        }
    }
}
