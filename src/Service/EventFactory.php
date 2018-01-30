<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Unit\UnitInterface;

/**
 * EventFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class EventFactory
{
    /**
     * @param UnitInterface $unit
     *
     * @return Event
     * @throws \Exception
     */
    public function buildEventByMonitorUnit(UnitInterface $unit): Event
    {
        $nextTime = new \DateTime('+'.$unit->getIdleTime().' minutes');

        return $this->buildEvent(
            $nextTime,
            $unit->getId(),
            $unit->getIdent()
        );
    }

    /**
     * @param \DateTime $nextCheck
     * @param int       $unitId
     * @param string    $unitIdent
     *
     * @return Event
     */
    public function buildEvent(\DateTime $nextCheck, int $unitId, string $unitIdent): Event
    {
        $event = $this->createNewEventInstance();
        $event->setNextCheck($nextCheck)
            ->setUnitId($unitId)
            ->setUnitIdent($unitIdent);

        return $event;
    }

    /**
     * @return Event
     */
    private function createNewEventInstance(): Event
    {
        return new Event();
    }


}