<?php

namespace Mbs\MbsBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Bus
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function sendMessage(string $type, array $params): ResponseInterface
    {
        $url = $this->getBusUrl() . '/messages/' . $type;

        $httpClient = HttpClient::create();
        return $httpClient->request('GET', $url, [
            'json' => $params
        ]);
    }

    /**
     * Register the service to bus
     *
     * @return ResponseInterface
     */
    public function register (): ResponseInterface
    {
        $url = $this->getBusUrl() . '/services';

        $httpClient = HttpClient::create();
        return $httpClient->request('POST', $url, [
            'json' => [
                'serviceName' => $this->container->getParameter('mbs.service.name'),
                'version' => $this->container->getParameter('mbs.service.version'),
                'ip' => $this->container->getParameter('mbs.service.ip'),
                'port' => $this->container->getParameter('mbs.service.port'),
                'url' => $this->container->getParameter('mbs.service.url'),
                'messageType' => $this->container->getParameter('mbs.service.messagesTypes'),
            ],
        ]);
    }

    protected function getBusUrl()
    {
        return $this->container->getParameter('mbs.server.protocol')
            . '://'
            . $this->container->getParameter('mbs.server.host')
            . ':'
            . $this->container->getParameter('mbs.server.port')
        ;
    }
}
