<?php

declare(strict_types=1);

namespace Norzechowicz\AceEditorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('norzechowicz_ace_editor');
        $rootNode = method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('norzechowicz_ace_editor');

        $rootNode
            ->children()
                ->booleanNode('autoinclude_ace')->defaultTrue()->end()
                ->scalarNode('base_path_ace')->defaultValue('vendor/ace')->end()
                ->booleanNode('debug_ace')->defaultFalse()->end()
                ->booleanNode('noconflict_ace')->defaultTrue()->end()
                ->booleanNode('autoinclude_json')->defaultTrue()->end()
                ->scalarNode('base_path_json')->defaultValue('vendor/jsoneditor')->end()
                ->booleanNode('debug_json')->defaultFalse()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
