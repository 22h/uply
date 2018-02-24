<?php

namespace App\Command\Loop;

use App\Service\MonitoringLoopService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * StatusCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:loop:status';

    /**
     * @var MonitoringLoopService
     */
    private $monitoringLoopService;

    /**
     * StatusCommand constructor.
     *
     * @param MonitoringLoopService $eventService
     */
    public function __construct(MonitoringLoopService $eventService)
    {
        $this->monitoringLoopService = $eventService;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        if (!$this->monitoringLoopService->isLoopProcessRunning()) {
            $symfonyStyle->warning('loop is inactive');
        } else {
            $symfonyStyle->success('loop is already running');
        }
    }
}
