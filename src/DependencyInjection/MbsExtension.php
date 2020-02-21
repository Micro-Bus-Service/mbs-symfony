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
            $container->setParameter('mbs.service.version', $config['service']['version']);
            $container->setParameter('mbs.service.ip', $config['service']['ip']);
            $container->setParameter('mbs.service.port', $config['service']['port'] ?? 80);
            $container->setParameter('mbs.service.url', $config['service']['url']);
            $container->setParameter('mbs.service.messagesTypes', $config['service']['messagesTypes']);
        }

        if (isset($config['server'])) {
            $container->setParameter('mbs.server.protocol', $config['server']['protocol'] ?? 'https');
            $container->setParameter('mbs.server.host', $config['server']['host']);
            $container->setParameter('mbs.server.port', $config['server']['port'] ?? 80);
        }
    }
}
