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
                ->booleanNode('autoinclude_ace_editor')->defaultTrue()->end()
                ->scalarNode('base_path_ace_editor')->defaultValue('bundles/norzechowiczaceeditor/ace')->end()
                ->booleanNode('autoinclude_jsoneditor')->defaultTrue()->end()
                ->scalarNode('base_path_jsoneditor')->defaultValue('bundles/norzechowiczaceeditor/jsoneditor')->end()
                ->booleanNode('debug')->defaultFalse()->end()
                ->booleanNode('noconflict')->defaultFalse()->end()
                // ->booleanNode('debug')->defaultTrue()->end()
                // ->booleanNode('noconflict')->defaultTrue()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
