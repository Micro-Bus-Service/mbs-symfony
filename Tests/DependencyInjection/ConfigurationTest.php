<?php

namespace Mbs\MbsBundle\Tests\DependencyInjection;

use Mbs\MbsBundle\Tests\TestCase;
use Mbs\MbsBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDoNoDuplicateDefaultFormResources()
    {
        $input = [
            'server' => ['protocol' => 'https'],
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$input]);

        $this->assertEquals('https', $config['server']['protocol']);
    }
}
