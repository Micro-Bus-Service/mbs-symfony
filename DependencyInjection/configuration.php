<?php

namespace Mbs\MbsBundle\DepencyInjection;

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
                    ->children()
                        ->scalarNode('serviceName')->end()
                        ->scalarNode('version')->end()
                        ->scalarNode('ip')->end()
                        ->port('port')->end()
                        ->url('url')->end()
                        ->arrayNode('messageType')
                            ->useAttributeAsKey('name')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}