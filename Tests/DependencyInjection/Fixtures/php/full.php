<?php

$container->loadFromExtension('mbs', [
  'server' => [
    'protocol' => 'https',
    'host' =>  'www.exemple.com',
    'port' => 80
  ],
  'service' => [
    'name' => 'Mbs',
    'version' => '0.1',
    'ip' => '0.0.0.0',
    'port' => 80,
    'url' => 'www.exemple.com/message',
    'messagesTypes' => [
      [
        'messageType' => 'messagetest',
        'class' =>  '\\App\\Messages\\Test'
      ]
    ] 
  ]
]);