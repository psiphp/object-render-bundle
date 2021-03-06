<?php

namespace Psi\Bundle\ObjectRender\Tests\Functional\Twig;

use Psi\Bundle\ObjectRender\Template\Locator;
use Psi\Bundle\ObjectRender\Twig\ObjectRenderExtension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem([
                __DIR__ . '/templates',
            ]),
            [
                'debug' => true,
                'strict_variables' => true,
            ]
        );
        $this->twig->addExtension(
            new ObjectRenderExtension(
                new Locator([
                    'Psi\\Bundle\\ObjectRender\\Tests\\Functional\\Twig' => 'objects',
                ], 'html.twig')
            )
        );
    }

    public function testExtension()
    {
        $output = $this->twig->render('render_object.html.twig', [
            'object' => new Test(),
        ]);
        $this->assertEquals(<<<'EOT'
Hello Daniel!


EOT
        , $output);
    }
}

class Test
{
    public $name = 'Daniel';
}
