<?php

namespace Mbs\MbsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('mbs');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('server')
                    ->children()
                        ->scalarNode('protocol')->end()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('service')
                    ->validate()
                        ->ifNotInArray(['service'])
                        ->thenInvalid('The %s type is not supported')
                    ->end()
                    ->children()
                        ->scalarNode('serviceName')->end()
                        ->scalarNode('version')->end()
                        ->scalarNode('ip')->end()
                        ->integerNode('port')->end()
                        ->scalarNode('url')->end()
                        // ->arrayNode('messagesTypes')
                        //     ->arrayPrototype()
                        //         ->children()
                        //             ->scalarNode('messageType')
                        //             ->scalarNode('class')
                        //         ->end()
                        //     ->end()
                        // ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
