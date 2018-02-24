<?php

namespace App\Command\Loop;

use App\Service\EventService;
use App\Service\MonitoringLoopService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SyncCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class SyncCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:loop:sync';

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * @var MonitoringLoopService
     */
    private $loopService;

    /**
     * SyncCommand constructor.
     *
     * @param EventService          $eventService
     * @param MonitoringLoopService $loopService
     */
    public function __construct(EventService $eventService, MonitoringLoopService $loopService)
    {
        $this->eventService = $eventService;
        $this->loopService = $loopService;

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
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->loopService->addSleepEvent();
        $this->eventService->syncUnitsInEvents($output);
        $this->loopService->removeSleepEvent();
    }
}
