<?php

namespace App\Monitor\Event;

use App\Monitor\UnitParameterBag;
use Symfony\Component\EventDispatcher\Event;

/**
 * MonitorNotifyEvent
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorNotifyEvent extends Event
{
    const NAME = 'uply.monitor.notify';

    /**
     * @var UnitParameterBag
     */
    private $parameterBag;

    /**
     * MonitorNotifyEvent
     *
     * @param UnitParameterBag $parameterBag
     */
    public function __construct(UnitParameterBag $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return UnitParameterBag
     */
    public function getParameterBag(): UnitParameterBag
    {
        return $this->parameterBag;
    }
}