<?php

namespace App\DependencyInjection;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * UplyConfiguration
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('uply');

        $rootNode
            ->children()
                ->arrayNode('monitoring_units')
                    ->useAttributeAsKey('ident')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('entity')->end()
                            ->scalarNode('repository')->end()
                            ->scalarNode('service')->end()
                            ->scalarNode('enabled')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('notifications')
                    ->useAttributeAsKey('ident')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('service')->end()
                            ->scalarNode('enabled')->end()
                        ->end()
                    ->end()
                ->end();
        // ... add node definitions to the root of the tree

        return $treeBuilder;
    }
}
