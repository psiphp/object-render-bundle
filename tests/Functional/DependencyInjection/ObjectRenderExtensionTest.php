<?php

namespace Psi\Bundle\ObjectRender\Tests\Functional\DependencyInjection;

use Psi\Bundle\ObjectRender\Example\app\AppKernel;

class ObjectRenderExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtension()
    {
        $extension = new ObjectRenderExtension();
    }
}
