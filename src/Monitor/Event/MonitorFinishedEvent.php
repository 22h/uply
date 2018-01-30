<?php

namespace App\Monitor\Event;

use App\Entity\Unit\UnitInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * MonitorFinishedEvent
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorFinishedEvent extends Event
{
    const NAME = 'uply.monitor.finished';

    /**
     * @var UnitInterface
     */
    private $monitorUnit;

    /**
     * MonitorFinishedEvent
     *
     * @param UnitInterface $monitorUnit
     */
    public function __construct(UnitInterface $monitorUnit)
    {
        $this->monitorUnit = $monitorUnit;
    }

    /**
     * @return UnitInterface
     */
    public function getMonitorUnit(): UnitInterface
    {
        return $this->monitorUnit;
    }
}