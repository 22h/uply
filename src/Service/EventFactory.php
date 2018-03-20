<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Unit\Backup;
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
        if($unit instanceof Backup) {
            $nextTime = new \DateTime(date('Y-m-d '.$unit->getInitialTime().':00:00',time() + 86400));
        }elseif($unit->isTriggered() && $unit->getTriggeredIdleTime() != 0) {
            $nextTime = new \DateTime('+'.$unit->getTriggeredIdleTime().' minutes');
        }else {
            $nextTime = new \DateTime('+'.$unit->getIdleTime().' minutes');
        }

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