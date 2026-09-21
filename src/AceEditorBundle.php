<?php

declare(strict_types=1);

namespace AceEditorBundle;

use AceEditorBundle\DependencyInjection\Compiler\TwigFormPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AceEditorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TwigFormPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function __toString(): string
    {
        return 'AceEditorBundle';
    }
}
