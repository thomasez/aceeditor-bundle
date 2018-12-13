<?php

namespace Norzechowicz\AceEditorBundle\Twig\Extension;

use Symfony\Bridge\Twig\Extension\AssetExtension;

class JsonEditorExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * Should we include the ace.js?
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
     * @var string
     */
    private $mode;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @param bool   $autoinclude means if the bundle should inclue the JS
     * @param string $basePath
     * @param string $mode
     */
    public function __construct($autoinclude, $basePath, $mode)
    {
        $this->editorIncluded = !$autoinclude;
        $this->basePath = rtrim($basePath, '/');
        $this->mode = ltrim($mode, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
error_log("initRuntimeJson");
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jsoneditor';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'include_jsoneditor' => new \Twig_SimpleFunction('include_jsoneditor',
                [$this, 'includeJsonEditor'],
                ['is_safe' => ['html']]
                ),
        ];
                // 'needs_environment' => true]
                // ['is_safe' => ['html'], 'needs_environment' => true]),
    }

    /**
     * Echoes the <script> tag.
     *
     * @throws \LogicException if asset extension is not available and Ace editor must be included
     */
    public function includeJsonEditor()
    {
error_log("includeJsonEditor");
        if ($this->editorIncluded) {
error_log("editorIncluded");
            return;
        }

        if (!$this->environment->hasExtension(AssetExtension::class)) {
            throw new \LogicException('"asset" extension is mandatory if you don\'t include Json editor by yourself.');
        }

/* May need this instead 
        $extension = "";
        if ($this->environment->hasExtension('asset')) {
            $extension = "asset";
        } elseif ($this->environment->hasExtension('assets')) {
            $extension = "assets";
        } else {
            return;
        }
*/

        if (!$this->editorIncluded) {
            foreach (['jsoneditor'] as $file) {
                /** @var AssetExtension $extension */
                $extension = $this->environment->getExtension(AssetExtension::class);
                $jsPath = $extension->getAssetUrl($this->basePath.'/'.$this->mode.'/'.$file.'.js');

                printf('<script src="%s" charset="utf-8" type="text/javascript"></script>', $jsPath);
            }
            $this->editorIncluded = true;
        }
    }
}
