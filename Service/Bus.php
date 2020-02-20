<?php

namespace Mbs\MbsBundle\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Bus
{
    protected $protocol;
    protected $host;
    protected $port;

    public function __construct()
    {
        $this->protocol=$_ENV['BUS_PROTOCOL'];
        $this->host=$_ENV['BUS_HOST'];
        $this->port=$_ENV['BUS_PORT'];
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
     * @todo parameters
     *
     * @return ResponseInterface
     */
    public function register (): ResponseInterface
    {
        $url = $this->getBusUrl() . '/services';

        $httpClient = HttpClient::create();
        return $httpClient->request('POST', $url, [
            'json' => [
                'serviceName' => '@todo',
                'version' => '@todo',
                'ip' => '@todo',
                'port' => '@todo',
                'url' => '@todo',
                'messageType' => '@todo'
            ],
        ]);
    }

    protected function getBusUrl()
    {
        return $this->protocol . '://' . $this->host . ':' . $this->port;
    }
}
