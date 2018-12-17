<?php

namespace Norzechowicz\AceEditorBundle\Twig\Extension;

use Symfony\Bridge\Twig\Extension\AssetExtension;

class JsonEditorExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * Should we include the editor
     * If false, user should include it it's own way.
     *
     * @var bool
     */
    private $editorIncluded;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @param bool   $autoinclude means if the bundle should inclue the JS
     * @param string $basePath
     */
    public function __construct($autoinclude, $basePath)
    {
        $this->editorIncluded = !$autoinclude;
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'json_editor';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'include_json_editor' => new \Twig_SimpleFunction('include_json_editor', [$this, 'includeJsonEditor'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Echoes the <script> tag.
     *
     * @throws \LogicException if asset extension is not available and Json editor must be included
     */
    public function includeJsonEditor()
    {
        if ($this->editorIncluded) {
            return;
        }

        if (!$this->environment->hasExtension(AssetExtension::class)) {
            throw new \LogicException('"asset" extension is mandatory if you don\'t include Json editor by yourself.');
        }

        if (!$this->editorIncluded) {
            foreach (['jsoneditor'] as $file) {
                /** @var AssetExtension $extension */
                $extension = $this->environment->getExtension(AssetExtension::class);
                $jsPath = $extension->getAssetUrl($this->basePath.'/'.$file.'.js');

                printf('<script src="%s" charset="utf-8" type="text/javascript"></script>', $jsPath);
                $cssPath = $extension->getAssetUrl($this->basePath.'/'.$file.'.css');

                printf('<link rel="stylesheet" href="%s" type="text/css" media="all" />', $cssPath);
            }
            $this->editorIncluded = true;
        }
    }
}
