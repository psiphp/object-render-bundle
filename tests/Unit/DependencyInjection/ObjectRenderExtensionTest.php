<?php

namespace Psi\Bundle\ObjectRender\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Psi\Bundle\ObjectRender\DependencyInjection\ObjectRenderExtension;

class ObjectRenderExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new ObjectRenderExtension();
    }

    /**
     * It should pass the mapping to the locator service.
     */
    public function testLocator()
    {
        $expectedMap = [
            '\\Foobar\\BarFoo' => 'path'
        ];
        $this->extension->load([
            [
                'mapping' => $expectedMap 
            ]
        ], $this->container);

        $definition = $this->container->getDefinition('psi_object_render.template.locator');
        $map = $definition->getArgument(0);
        $this->assertEquals($expectedMap, $map);
    }

    /**
     * It should sort the namespaces.
     */
    public function testNamespaceSort()
    {
        $this->extension->load([
            [
                'mapping' => [
                    '\\Foobar\\BarFoo' => 'path',
                    '\\Foobar' => 'path/to',
                    '\\Foobar\\BarFoo\\Foobar' => 'path'
                ]
            ]
        ], $this->container);

        $definition = $this->container->getDefinition('psi_object_render.template.locator');
        $map = $definition->getArgument(0);
        $this->assertEquals([
            '\\Foobar\\BarFoo\\Foobar',
            '\\Foobar\\BarFoo',
            '\\Foobar',
        ], array_keys($map));
    }
}
