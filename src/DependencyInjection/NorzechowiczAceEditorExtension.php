<?php

declare(strict_types=1);

namespace Norzechowicz\AceEditorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NorzechowiczAceEditorExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerAceEditorParameters($config, $container);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('form.xml');
        $loader->load('twig.xml');
    }

    /**
     * Register parameters for the DI.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function registerAceEditorParameters(array $config, ContainerBuilder $container)
    {
        // use debug from the kernel.debug, but we can force it via "debug"
        $debug = $container->getParameter('kernel.debug');
        if (!$debug && $config['debug_ace']) {
            $debug = true;
        }

        $mode_ace = 'src'.($debug ? '' : '-min').($config['noconflict_ace'] ? '-noconflict' : '');

        $container->setParameter('norzechowicz_ace_editor.options.autoinclude_ace', $config['autoinclude_ace']);
        $container->setParameter('norzechowicz_ace_editor.options.base_path_ace', $config['base_path_ace']);
        $container->setParameter('norzechowicz_ace_editor.options.mode_ace', $mode_ace);
        $container->setParameter('norzechowicz_ace_editor.options.autoinclude_json', $config['autoinclude_json']);
        $container->setParameter('norzechowicz_ace_editor.options.base_path_json', $config['base_path_json']);
    }
}