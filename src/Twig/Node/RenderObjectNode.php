<?php

namespace Psi\Bundle\ObjectRender\Twig\Node;

class RenderObjectNode extends \Twig_Node_Expression_Function
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $this->addGetTemplate($compiler);
        $compiler->raw('->display(');
        $this->addTemplateArguments($compiler);
        $compiler->raw(')');
    }

    protected function addGetTemplate(\Twig_Compiler $compiler)
    {
        $args = $this->getNode('arguments');

        if (!$args->hasNode(0)) {
            throw new \Twig_Error_Runtime(sprintf(
                'Function "%s" requires an object as its first argument',
                $this->getAttribute('name')
            ));
        }
        $objectArg = $args->getNode(0);

        $variant = null;
        if ($args->hasNode(1)) {
            $variant = $args->getNode(1);
        }
        $node = $compiler
            ->write('$this->loadTemplate(')
            ->write('$this->env->getExtension("Psi\Bundle\ObjectRender\Twig\ObjectRenderExtension")->locateFile(')
            ->subcompile($objectArg);

        if ($variant) {
            $node
                ->raw(', ')
                ->subCompile($variant);
        }

        $node
            ->raw('), ')
            ->repr($this->getAttribute('name'))
            ->raw(', ')
            ->repr($this->getLine())
            ->raw(')');
    }

    protected function addTemplateArguments(\Twig_Compiler $compiler)
    {
        $object = $this->getNode('arguments');
        $object = $object->getNode(0);
        $compiler->raw('[ "object" => ');
        $compiler->subcompile($object);
        $compiler->raw(']');
    }
}
