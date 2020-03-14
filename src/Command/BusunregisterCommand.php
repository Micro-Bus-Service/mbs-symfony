<?php

namespace Mbs\MbsBundle\Command;

use Mbs\MbsBundle\Service\Bus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BusunregisterCommand extends Command
{
    protected static $defaultName = 'bus:unregister';

    protected $bus;

    public function __construct(Bus $bus, string $name = null)
    {
        parent::__construct($name);
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Unregister this service on bus')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $registrationResponse = $this->bus->register();

        if ($registrationResponse->getStatusCode() === 201) {
            $io->success('Unregistrated');
        } else {
            $io->error('Unregistration failed width message : ' . $registrationResponse->getContent());
        }
    }
}
