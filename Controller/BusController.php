<?php

namespace Mbs\MbsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusController extends AbstractController
{
    /**
     * @Route("/bus/getMessage", name="bus")
     * @todo message listened to external class
     */
    public function index(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        $type = $body['type'];
        $message = $body['message'];
        
        switch ($type) {
            case '@todo knowed message type':
                $status = 201;
                $body = json_encode([]);
                break;
            
            default:
                $status = 404;
                $body = json_encode([]);
                break;
        }
        
        return new Response($body, $status);
    }
}