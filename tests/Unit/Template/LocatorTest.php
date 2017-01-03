<?php

namespace Psi\Bundle\ObjectRender\Tests\Unit\Template;

use Psi\Bundle\ObjectRender\Template\Locator;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    private $reflection;

    public function setUp()
    {
        $this->reflection = $this->prophesize(\ReflectionClass::class);
    }

    /**
     * It should resolve a class to a filename.
     *
     * @dataProvider provideLocate
     */
    public function testLocate($classFqn, $suffix, $expectedFname, $variant = null)
    {
        $resolver = $this->create([
            '\\Some\\Other\\Namespace' => '/path/to/d',
            '\\Foobar\\Barfoo' => '/path/to/a',
            '\\Foobar\\Foofoo' => '/path/to/a',
            '\\' => '/path/to/c',
        ], $suffix);

        $this->reflection->getName()->willReturn($classFqn);
        $result = $resolver->locate($this->reflection->reveal(), $variant);

        $this->assertEquals($expectedFname, $result);
    }

    public function provideLocate()
    {
        return [
            'matches longest namespace' => [
                '\\Foobar\\Barfoo\\View\\ObjectView',
                'html.twig',
                '/path/to/a/View.ObjectView.html.twig',
            ],
            'falls back to "global" namespace' => [
                '\\Aoobar\\Aarfoo\\View\\ObjectView',
                'html.twig',
                '/path/to/c/Aoobar.Aarfoo.View.ObjectView.html.twig',
            ],
            'suffix is configurable' => [
                '\\Aoobar\\Aarfoo\\View\\ObjectView',
                'php',
                '/path/to/c/Aoobar.Aarfoo.View.ObjectView.php',
            ],
            'exact matches do not count' => [
                '\\Foobar\\Barfoo',
                'php',
                '/path/to/c/Foobar.Barfoo.php',
            ],
            'with variant' => [
                '\\Foobar\\Barfoo\\View\\ObjectView',
                'html.twig',
                '/path/to/a/View.ObjectView/variant_one.html.twig',
                'variant_one',
            ],
        ];
    }

    /**
     * It should throw an exception if the object namespace is not in the template map.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not resolve path for class "\Foo\Bar" in namespaces
     */
    public function testNotMapped()
    {
        $resolver = $this->create([
            '\\Foobar\\Barfoo' => '/path/to/a',
        ], '.twig');

        $this->reflection->getName()->willReturn('\\Foo\\Bar');
        $resolver->locate($this->reflection->reveal());
    }

    public function create(array $map, $suffix)
    {
        return new Locator($map, $suffix);
    }
}
