<?php

namespace Mbs\MbsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('mbs');
        $rootNode = $treeBuilder->getRootNode();

        $this->addServerSection($rootNode);
        $this->addServiceSection($rootNode);

        return $treeBuilder;
    }

    private function addServerSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('server')
                    ->children()
                        ->scalarNode('protocol')->end()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                    ->end()
                ->end()
            ->end();

    }

    private function addServiceSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('service')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('version')->end()
                        ->scalarNode('ip')->end()
                        ->integerNode('port')->end()
                        ->scalarNode('url')->end()
                        ->arrayNode('messagesTypes')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('messageType')->end()
                                    ->scalarNode('class')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
