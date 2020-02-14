<?php

namespace Mbs\MbsBundle\Tests\DependencyInjection;

use Mbs\MbsBundle\DependencyInjection\MbsExtension;
use Mbs\MbsBundle\MbsBundle;
use Mbs\MbsBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
}
