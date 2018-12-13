<?php

namespace Norzechowicz\AceEditorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
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
        if (!$debug && $config['debug']) {
            $debug = true;
        }

        $mode = 'src'.($debug ? '' : '-min').($config['noconflict'] ? '-noconflict' : '');

        $container->setParameter('norzechowicz_ace_editor.options.autoinclude_ace_editor', $config['autoinclude_ace_editor']);
        $container->setParameter('norzechowicz_ace_editor.options.base_path_ace_editor', $config['base_path_ace_editor']);
        $container->setParameter('norzechowicz_ace_editor.options.mode_ace_editor', $mode);

        $container->setParameter('norzechowicz_ace_editor.options.autoinclude_jsoneditor', $config['autoinclude_jsoneditor']);
        $container->setParameter('norzechowicz_ace_editor.options.base_path_jsoneditor', $config['base_path_jsoneditor']);
        $container->setParameter('norzechowicz_ace_editor.options.mode_jsoneditor', $mode);
    }
}
