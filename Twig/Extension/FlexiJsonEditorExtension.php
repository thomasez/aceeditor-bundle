<?php

/**
 * This file is part of the AceEditorBundle.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Norzechowicz\AceEditorBundle\Twig\Extension;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 * Hacked from AceEditorExtension by Thomas Lundquist <github@bisonlab.no>
 */
class FlexiJsonEditorExtension extends \Twig_Extension
{
    /**
     * @var boolean
     */
    protected $editorIncluded;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct($autoinclude, $basePath)
    {
        $this->ckeditorIncluded = $autoinclude;
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'flexijsoneditor';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'include_flexijsoneditor',
                array($this, 'includeFlexiJsonEditor'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            )
        );
    }

    public function includeFlexiJsonEditor(\Twig_Environment $environment)
    {
        $this->environment = $environment;

        $extension = "";
        if ($this->environment->hasExtension('asset')) {
            $extension = "asset";
        } elseif ($this->environment->hasExtension('assets')) {
            $extension = "assets";
        } else {
            return;
        }

        if (!$this->editorIncluded) {
            $this->editorIncluded = true;
        }

        if (!$this->ckeditorIncluded) {
            $jsPath = $this->environment
                ->getExtension($extension)
                ->getAssetUrl($this->basePath);
            echo sprintf('<link href="%s" rel="stylesheet" type="text/css">', $jsPath . '/jsoneditor.css');
            echo sprintf('<script src="%s" charset="utf-8"></script>', $jsPath . '/json2.js');
            echo sprintf('<script src="%s" charset="utf-8"></script>', $jsPath . '/jquery.jsoneditor.js');
            // echo sprintf('<script src="%s" charset="utf-8"></script>', $jsPath . '/jsoneditor.js');
            $this->ckeditorIncluded = true;
        }
    }
}
