<?php

namespace Mbs\MbsBundle\Tests\DependencyInjection;

use Mbs\MbsBundle\DepencyInjection\MbsExtension;
use Mbs\MbsBundle\Tests\TestCase;

class ConfigurationTest extends TestCase
{
    public function testLoadEmptyConfiguration()
    {
        $container = $this->createContainer();
        $container->registerExtension(new MbsExtension());
        $container->loadFromExtension('twig');
        $this->compileContainer($container);

        $this->assertEquals('Twig\Environment', $container->getDefinition('twig')->getClass(), '->load() loads the twig.xml file');

        $this->assertContains('form_div_layout.html.twig', $container->getParameter('twig.form.resources'), '->load() includes default template for form resources');

        // Twig options
        $options = $container->getDefinition('twig')->getArgument(1);
        $this->assertEquals('%kernel.cache_dir%/twig', $options['cache'], '->load() sets default value for cache option');
        $this->assertEquals('%kernel.charset%', $options['charset'], '->load() sets default value for charset option');
        $this->assertEquals('%kernel.debug%', $options['debug'], '->load() sets default value for debug option');
    }
}
