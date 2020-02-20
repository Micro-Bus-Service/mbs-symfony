<?php

namespace Mbs\MbsBundle\Tests\DependencyInjection;

use Mbs\MbsBundle\DependencyInjection\MbsExtension;
use Mbs\MbsBundle\MbsBundle;
use Mbs\MbsBundle\Tests\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MbsExtensionTest extends TestCase
{
    public function testLoadEmptyConfiguration()
    {
        $container = $this->createContainer();
        $container->registerExtension(new MbsExtension());
        $container->loadFromExtension('mbs');
        $this->compileContainer($container);

        $this->assertInstanceOf('Mbs\MbsBundle\DependencyInjection\MbsExtension', $container->getExtension('mbs'));
    }

    /**
     * @dataProvider getFormats
     */
    public function testLoadFullConfiguration($format)
    {
        $container = $this->createContainer();
        $container->registerExtension(new MbsExtension());
        $this->loadFromFile($container, 'full', $format);
        $this->compileContainer($container);

        $this->assertInstanceOf('Mbs\MbsBundle\DependencyInjection\MbsExtension', $container->getExtension('mbs'));

        // Server
        $this->assertEquals('https', $container->getParameter('mbs.server.protocol'));
        $this->assertEquals('www.exemple.com', $container->getParameter('mbs.server.host'));
        $this->assertEquals(80, $container->getParameter('mbs.server.port'));

        // Service
        $this->assertEquals('Mbs', $container->getParameter('mbs.service.name'));
        $this->assertEquals('0.1', $container->getParameter('mbs.service.version'));
        $this->assertEquals('0.0.0.0', $container->getParameter('mbs.service.ip'));
        $this->assertEquals(80, $container->getParameter('mbs.service.port'));
        $this->assertEquals('www.exemple.com/message', $container->getParameter('mbs.service.url'));
        $this->assertEquals([['messageType' => 'messagetest', 'class' => '\\App\\Messages\\Test']], $container->getParameter('mbs.service.messagesTypes'));
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.project_dir' => __DIR__,
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => false,
            'kernel.bundles' => [
                'MbsBundle' => MbsBundle::class,
            ],
            'kernel.bundles_metadata' => [
                'MbsBundle' => [
                    'namespace' => 'Mbs\MbsBundle\DependencyInjection\MbsBundle',
                    'path' => __DIR__ . '/MbsBundle',
                ],
            ],
        ]));

        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();
    }

    public function getFormats()
    {
        return [
            ['php'],
            ['yml'],
        ];
    }

    private function loadFromFile(ContainerBuilder $container, $file, $format)
    {
        $locator = new FileLocator(__DIR__.'/Fixtures/'.$format);

        switch ($format) {
            case 'php':
                $loader = new PhpFileLoader($container, $locator);
                break;
            case 'yml':
                $loader = new YamlFileLoader($container, $locator);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported format: %s', $format));
        }

        $loader->load($file.'.'.$format);
    }
}
