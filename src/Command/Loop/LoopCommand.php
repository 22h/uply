<?php

namespace App\Command\Loop;

use App\Entity\Event;
use App\Service\EventService;
use App\Service\MonitoringLoopService;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * LoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class LoopCommand extends ContainerAwareCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const COMMAND_NAME = 'monitor:loop:loop';

    /**
     * @var EventService
     */
    protected $eventService;

    /**
     * @var MonitoringLoopService
     */
    private $monitoringLoopService;

    /**
     * LoopCommand constructor.
     *
     * @param EventService          $eventService
     * @param MonitoringLoopService $monitoringLoopService
     */
    public function __construct(EventService $eventService, MonitoringLoopService $monitoringLoopService)
    {
        $this->eventService = $eventService;
        $this->monitoringLoopService = $monitoringLoopService;

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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        if ($this->monitoringLoopService->isLoopProcessRunning()) {
            $symfonyStyle->success('loop is already running');

            return;
        }

        while (true) {
            $event = $this->eventService->findNextUnit();

            if ($event instanceof Event) {
                $this->cycle($event);
            } else {
                sleep(1);
            }
        }
    }

    /**
     * @param Event $event
     */
    private function cycle(Event $event): void
    {
        if ($event->isSleepEvent()) {
            sleep(10);

            return;
        }
        if ($event->isExitEvent()) {
            $this->removeEvent($event);
            exit();
        }

        $unitId = $event->getUnitId();
        $unitType = $event->getUnitIdent();

        $this->removeEvent($event);
        shell_exec('php bin/console monitor:monitor '.$unitId.' '.$unitType.' > /dev/null 2>/dev/null &');
    }

    /**
     * @param Event $event
     */
    private function removeEvent(Event $event): void
    {
        try {
            $this->eventService->removeEvent($event);
        } catch (ORMException $exception) {
            $this->logger->error(
                'can not delete event with id: 
                    '.$event->getId().'. '.$exception->getMessage()
            );
        }
    }
}