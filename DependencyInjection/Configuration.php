<?php

namespace Norzechowicz\AceEditorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('norzechowicz_ace_editor');

        $rootNode
            ->children()
                ->booleanNode('autoinclude')->defaultTrue()->end()
                ->scalarNode('base_path')->defaultValue('bundles/norzechowiczaceeditor/ace')->end()
                ->booleanNode('jsoneditor_autoinclude')->defaultTrue()->end()
                ->scalarNode('base_path_jsoneditor')->defaultValue('bundles/norzechowiczaceeditor/jsoneditor')->end()
                ->booleanNode('flexijsoneditor_autoinclude')->defaultTrue()->end()
                ->scalarNode('base_path_flexijsoneditor')->defaultValue('bundles/norzechowiczaceeditor/flexijsoneditor')->end()
                ->booleanNode('debug')->defaultTrue()->end()
                // ->booleanNode('debug')->defaultFalse()->end()
                ->booleanNode('noconflict')->defaultTrue()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
