<?php

namespace App\Service;

use App\Command\Loop\LoopCommand;
use App\Entity\Event;

/**
 * MonitoringLoopService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitoringLoopService
{
    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * MonitoringLoopService constructor.
     *
     * @param EventFactory $eventFactory
     * @param EventService $eventService
     */
    public function __construct(EventFactory $eventFactory, EventService $eventService)
    {
        $this->eventFactory = $eventFactory;
        $this->eventService = $eventService;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSleepEvent(): void
    {
        $event = $this->eventFactory->buildEvent(
            (new \DateTime('2000-01-01')),
            0,
            Event::UNIT_SPECIAL_TYPE_SLEEP
        );

        $this->eventService->storeEvent($event);
    }

    /**
     * @throws \Exception
     */
    public function removeSleepEvent(): void
    {
        $affectedRows = $this->eventService->deleteByUnitTypeAndId(0, Event::UNIT_SPECIAL_TYPE_SLEEP);

        if($affectedRows < 1) {
            throw new \Exception('The event cannot be deleted because there is none.');
        }
    }

    /**
     * @return bool
     */
    public function isLoopProcessRunning(): bool
    {
        $lines = [];

        exec('ps -ef | grep "bin/console '.LoopCommand::COMMAND_NAME.'"', $lines);

        $count = 0;
        foreach ($lines as $line) {
            if (strpos($line, 'php bin/console '.LoopCommand::COMMAND_NAME) !== false) {
                $count++;
            }
        }

        return ($count > 1);
    }

    public function startLoopProcessNow(): void
    {
        shell_exec('php bin/console '.LoopCommand::COMMAND_NAME.' > /dev/null 2>/dev/null &');
    }
}