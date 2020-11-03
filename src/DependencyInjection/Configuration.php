<?php

declare(strict_types=1);

namespace Yproximite\Bundle\YproxApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('yprox_api_client');

        // @phpstan-ignore-next-line
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('yprox_api_client'); // @phpstan-ignore-line
        }

        $rootNode
            ->children()
                ->scalarNode('http_client')
                    ->info(sprintf('Identifier of the service that represents "%s"', 'Http\\Client\\HttpClient'))
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('clients')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('base_url')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
