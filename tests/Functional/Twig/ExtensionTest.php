<?php

namespace Psi\Bundle\ObjectTemplate\Tests\Functional\Twig;

use Psi\Bundle\ObjectTemplate\Twig\ObjectTemplateExtension;
use Psi\Bundle\ObjectTemplate\Template\Locator;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem([
                __DIR__ . '/templates'
            ]),
            [
                'debug' => true,
                'strict_variables' => true,
            ]
        );
        $this->twig->addExtension(
            new ObjectTemplateExtension(
                new Locator([
                    'Psi\\Bundle\\ObjectTemplate\\Tests\\Functional\\Twig' => 'objects'
                ], 'html.twig')
            )
        );
    }

    public function testExtension()
    {
        $output = $this->twig->render('render_object.html.twig', [
            'object' => new Test(),
        ]);
        $this->assertEquals(<<<EOT
Hello Daniel!


EOT
        , $output);
    }
}

class Test
{
    public $name = 'Daniel';
}
