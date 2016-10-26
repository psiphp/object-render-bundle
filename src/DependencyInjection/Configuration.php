<?php

namespace Psi\Bundle\ObjectRender\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('psi_object_render');
        $rootNode->children()
            ->arrayNode('mapping')
                ->info('Path mapping for object template location')
                ->useAttributeAsKey('namespace')
                ->prototype('scalar')->info('namespace => path')->end()
            ->end();

        return $treeBuilder;
    }
}
