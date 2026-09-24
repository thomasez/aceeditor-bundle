<?php

declare(strict_types=1);

namespace AceEditorBundle\DependencyInjection;

use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\UX\StimulusBundle\StimulusBundle;

class AceEditorExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerAceEditorParameters($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('form.yaml');
        $loader->load('twig.yaml');
        // Setting @ in front during the YamlFileLoading strips it.
        // With XmlFileLoader it does not.
        if ($fr = $container->getParameter('ace_editor.form.resource'))
            $container->setParameter('ace_editor.form.resource', '@'.$fr);
        if ($fr = $container->getParameter('ace_editor.json_form.resource'))
            $container->setParameter('ace_editor.json_form.resource', '@'.$fr);
    }

    /**
     * @see https://symfony.com/doc/current/frontend/create_ux_bundle.html
     */
    public function prepend(ContainerBuilder $container): void
    {
        if ($this->isAssetMapperAvailable($container)) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        __DIR__ . '/../../assets/controllers' => 'norberttech/aceeditor-bundle',
                    ],
                ],
            ]);
        }
    }

    /**
     * Register parameters for the DI.
     *
     * @param array<string, null|bool|float|int|string> $config
     */
    private function registerAceEditorParameters(array $config, ContainerBuilder $container): void
    {
        // use debug from the kernel.debug, but we can force it via "debug"
        $debug = $container->getParameter('kernel.debug');
        if (!$debug && $config['debug']) {
            $debug = true;
        }

        $mode = 'src' . ($debug ? '' : '-min') . ($config['noconflict'] ? '-noconflict' : '');

        $useStimulus = $config['use_stimulus'];
        if (null === $useStimulus) {
            $bundles = $container->getParameter('kernel.bundles');
            \assert(\is_array($bundles));
            $useStimulus = \in_array(StimulusBundle::class, $bundles, true) && interface_exists(AssetMapperInterface::class);
        }

        // AceEditor
        $container->setParameter('ace_editor.options.autoinclude', $config['autoinclude']);
        $container->setParameter('ace_editor.options.base_path', $config['base_path']);
        $container->setParameter('ace_editor.options.mode', $mode);
        $container->setParameter('ace_editor.options.use_stimulus', $useStimulus);
        // JsonEditor
        $container->setParameter('ace_editor.options.autoinclude_json', $config['autoinclude_json']);
        $container->setParameter('ace_editor.options.base_path_json', $config['base_path_json']);
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        // check that FrameworkBundle 6.3 or higher is installed
        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'] . '/Resources/config/asset_mapper.php');
    }
}
