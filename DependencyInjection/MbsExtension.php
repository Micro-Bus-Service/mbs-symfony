<?php

namespace Mbs\MbsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class MbsExtension extends Extension
{
    /**
     * Loads a specific configuration
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['service'])) {
            $container->setParameter('mbs.service.name', $config['service']['name']);
        }
    }
}
