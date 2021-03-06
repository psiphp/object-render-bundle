<?php

namespace Psi\Bundle\ObjectRender\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PsiObjectRenderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $locator = $container->getDefinition('psi_object_render.template.locator');

        // sort by namespace length
        $mapping = $config['mapping'];
        uksort($mapping, function ($one, $two) {
            return strlen($two) - strlen($one);
        });
        $locator->replaceArgument(0, $mapping);
    }
}
