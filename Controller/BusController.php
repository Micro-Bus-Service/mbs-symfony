<?php

namespace Mbs\MbsBundle\Controller;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusController
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * @Route("/bus/getMessage", name="bus")
     */
    public function index(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        $type = $body['type'];
        $message = $body['message'];

        $status = 501;
        $responseBody = 'messageType ' . $type . ' not reconize by this service';
        $messagesTypes = $this->container->getParameter('mbs.service.messagesTypes');

        foreach ($messagesTypes as $messageType) {
            if ($messageType['messageType'] == $type) {
                $class = $messageType['class'];
                if (is_callable($class)) {
                    $status = 201;
                    $responseBody = $class($message);
                } else {
                    $status = 501;
                    $responseBody = 'The message type is not correctly implemented.';
                }
                break;
            }
        }
        
        return new Response($responseBody, $status);
    }
}