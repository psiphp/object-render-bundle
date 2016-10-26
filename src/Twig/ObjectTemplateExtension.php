<?php

namespace Psi\Bundle\ObjectTemplate\Twig;

use Psi\Bundle\ObjectTemplate\Twig\Node\RenderObjectNode;
use Psi\Bundle\ObjectTemplate\Template\Locator;

class ObjectTemplateExtension extends \Twig_Extension
{
    private $locator;

    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('psi_render_object', null, [
                'node_class' => RenderObjectNode::class
            ], [
                'is_safe' => [ 'html' ]
            ])
        ];
    }

    public function locateFile($object)
    {
        $reflection = new \ReflectionClass($object);
        return $this->locator->locate($reflection);
    }
}
