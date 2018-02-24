<?php

namespace App\Monitor\Event;

use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitParameterBag;
use App\Monitor\UnitParameterBagFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * NotifyEventFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotifyEventDispatcher
{

    /**
     * @var UnitParameterBagFactory
     */
    protected $parameterBagFactory;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * NotifyEventFactory constructor.
     *
     * @param UnitParameterBagFactory  $parameterBagFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UnitParameterBagFactory $parameterBagFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->parameterBagFactory = $parameterBagFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $unit
     * @param $title
     * @param $message
     * @param $alert
     */
    public function dispatchNotification(
        UnitInterface $unit,
        string $title,
        string $message,
        ?string $alert = null
    ): void {
        $parameterBag = $this->parameterBagFactory->createParameterBag(
            $unit,
            $message,
            $title,
            $alert
        );

        $event = $this->newNotifyEvent($parameterBag);
        $this->eventDispatcher->dispatch(MonitorNotifyEvent::NAME, $event);
    }

    /**
     * @param UnitParameterBag $parameterBag
     *
     * @return MonitorNotifyEvent
     */
    private function newNotifyEvent(UnitParameterBag $parameterBag)
    {
        return new MonitorNotifyEvent($parameterBag);
    }


}