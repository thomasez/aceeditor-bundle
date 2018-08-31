<?php

namespace Norzechowicz\AceEditorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TwigFormPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }

        $container->setParameter('twig.form.resources', array_merge(
            array(
            'NorzechowiczAceEditorBundle:Form:div_layout_ace.html.twig',
            'NorzechowiczAceEditorBundle:Form:div_layout_jsoneditor.html.twig',
            'NorzechowiczAceEditorBundle:Form:div_layout_flexijsoneditor.html.twig',
            ),
            $container->getParameter('twig.form.resources')
        ));
    }
}
