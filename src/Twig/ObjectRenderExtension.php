<?php

namespace Psi\Bundle\ObjectRender\Twig;

use Psi\Bundle\ObjectRender\Template\Locator;
use Psi\Bundle\ObjectRender\Twig\Node\RenderObjectNode;

class ObjectRenderExtension extends \Twig_Extension
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
                'node_class' => RenderObjectNode::class,
            ], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function locateFile($object, string $variant = null)
    {
        $reflection = new \ReflectionClass($object);

        return $this->locator->locate($reflection, $variant);
    }
}
