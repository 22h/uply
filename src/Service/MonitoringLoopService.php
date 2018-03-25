<?php

namespace App\Service;

use App\Command\Loop\LoopCommand;
use App\Entity\Event;
use App\Monitor\UnitServiceChain;

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
     * @var UnitServiceChain
     */
    private $unitServiceChain;

    /**
     * MonitoringLoopService constructor.
     *
     * @param EventFactory     $eventFactory
     * @param EventService     $eventService
     * @param UnitServiceChain $unitServiceChain
     */
    public function __construct(
        EventFactory $eventFactory,
        EventService $eventService,
        UnitServiceChain $unitServiceChain
    ) {
        $this->eventFactory = $eventFactory;
        $this->eventService = $eventService;
        $this->unitServiceChain = $unitServiceChain;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addExitEvent(): void
    {
        $event = $this->eventFactory->buildEvent(
            (new \DateTime('2000-01-01')),
            0,
            Event::UNIT_SPECIAL_TYPE_EXIT
        );

        $this->eventService->storeEvent($event);
    }

    /**
     * @throws \Exception
     */
    public function removeSleepEvent(): void
    {
        $affectedRows = $this->eventService->deleteByUnitTypeAndId(0, Event::UNIT_SPECIAL_TYPE_SLEEP);

        if ($affectedRows < 1) {
            throw new \Exception('The event cannot be deleted because there is none.');
        }
    }

    /**
     * @param int $plus
     *
     * @return bool
     */
    public function isLoopProcessRunning(int $plus = 0): bool
    {
        $lines = [];

        exec('ps -ef | grep "bin/console '.LoopCommand::COMMAND_NAME.'"', $lines);

        $count = 0;
        foreach ($lines as $line) {
            if (strpos($line, 'php bin/console '.LoopCommand::COMMAND_NAME) !== false) {
                $count++;
            }
        }

        return ($count > $plus);
    }

    /**
     * @return void
     */
    public function startLoopProcessNow(): void
    {
        shell_exec('php bin/console '.LoopCommand::COMMAND_NAME.' > /dev/null 2>/dev/null &');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllTriggeredUnits(): array
    {
        $unitIdentities = $this->unitServiceChain->getIdentifier();

        $output = [];

        foreach ($unitIdentities as $unitIdent) {
            $output[$unitIdent] = $this->getAllTriggeredUnitsByIdent($unitIdent);
        }

        return $output;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function countAllUnits(): array
    {
        $unitIdentities = $this->unitServiceChain->getIdentifier();

        $output = [];

        foreach ($unitIdentities as $unitIdent) {
            $output[$unitIdent] = $this->countUnitsByIdent($unitIdent);
        }

        return $output;
    }

    /**
     * @param string $ident
     *
     * @return int
     * @throws \Exception
     */
    public function countUnitsByIdent(string $ident): int
    {
        $unitService = $this->unitServiceChain->getUnitService($ident);
        $repository = $unitService->getRepository();

        return $repository->countUnits();
    }

    /**
     * @param string $ident
     *
     * @return array
     * @throws \Exception
     */
    private function getAllTriggeredUnitsByIdent(string $ident): array
    {
        $unitService = $this->unitServiceChain->getUnitService($ident);
        $repository = $unitService->getRepository();

        return $repository->findTriggeredUnits();
    }
}