<?php

namespace Mbs\MbsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;

class Bus
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The key in cache
     *
     * @var string
     */
    protected $tokenCacheName = 'mbs.token';

    /**
     * @var string
     */
    protected static $token;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Send the message to bus
     *
     * @param string $type
     * @param array|string $params
     * @return ResponseInterface
     */
    public function sendMessage(string $type, $params): ResponseInterface
    {
        $cache = new FilesystemAdapter();

        $url = $this->getBusUrl() . '/messages/' . $type;
        $token = $cache->getItem($this->tokenCacheName);

        if (!$token->isHit()) {
            $this->register();
            $token = $cache->getItem($this->tokenCacheName);
        }

        $httpClient = HttpClient::create();
        return $httpClient->request('POST', $url, [
            'json' => 
                [
                    'uuid' => $token->get(),
                    'message' => $params,
                ]
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

        $messagesTypes = [];

        foreach ($this->container->getParameter('mbs.service.messagesTypes') as $messageType) {
            $messagesTypes[] = $messageType['messageType'];
        }
        
        $httpClient = HttpClient::create();
        $return = $httpClient->request('POST', $url, [
            'json' => [
                'serviceName' => $this->container->getParameter('mbs.service.name'),
                'version' => $this->container->getParameter('mbs.service.version'),
                'ip' => $this->container->getParameter('mbs.service.ip'),
                'port' => $this->container->getParameter('mbs.service.port'),
                'url' => $this->container->getParameter('mbs.service.url'),
                'messageType' => $messagesTypes,
            ],
        ]);
        
        $content = json_decode($return->getContent(), true);
        $cache = new FilesystemAdapter();
        $token = $cache->getItem($this->tokenCacheName);
        switch ($return->getStatusCode()) {
            case 201:
                $token->set($content['uuid']);
                break;
            case 422:
                if (isset($content['uuid'])) {
                    $token->set($content['uuid']);
                }
            
            default:
            break;
        }
        $cache->save($token);
        
        return $return;
    }

    public function unregister(): ResponseInterface
    {
        $cache = new FilesystemAdapter();
        $token = $cache->getItem($this->tokenCacheName);
        
        if (!$token->isHit())  {
            throw new \Exception('This service is already unregitered');
        }
        
        $url = $this->getBusUrl() . '/services/' . $token->get();
        
        $httpClient = HttpClient::create();
        $return = $httpClient->request(Request::METHOD_DELETE, $url);
        
        switch ($return->getStatusCode()) {
            case 201:
                $cache->deleteItem($this->tokenCacheName);
                break;
            
            default:
                break;
        }
        $cache->save($token);

        return $return;

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
