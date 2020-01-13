<?php

namespace Norzechowicz\AceEditorBundle\Twig\Extension;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Twig\Extension\AbstractExtension;
use Twig\Extension\InitRuntimeInterface;
use Twig\Environment;
use Twig\TwigFunction;

class JsonEditorExtension extends AbstractExtension
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
            'include_json_editor' => new TwigFunction('include_json_editor', [$this, 'includeJsonEditor'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * Echoes the <script> tag.
     *
     * @throws \LogicException if asset extension is not available and Json editor must be included
     */
    public function includeJsonEditor(Environment $environment)
    {
        if ($this->editorIncluded) {
            return;
        }

        if (!$environment->hasExtension(AssetExtension::class)) {
            throw new \LogicException('"asset" extension is mandatory if you don\'t include Json editor by yourself.');
        }

        if (!$this->editorIncluded) {
            foreach (['jsoneditor'] as $file) {
                /** @var AssetExtension $extension */
                $extension = $environment->getExtension(AssetExtension::class);
                $jsPath = $extension->getAssetUrl($this->basePath.'/'.$file.'.js');

                printf('<script src="%s" charset="utf-8" type="text/javascript"></script>', $jsPath);
                $cssPath = $extension->getAssetUrl($this->basePath.'/'.$file.'.css');

                printf('<link rel="stylesheet" href="%s" type="text/css" media="all" />', $cssPath);
            }
            $this->editorIncluded = true;
        }
    }
}
