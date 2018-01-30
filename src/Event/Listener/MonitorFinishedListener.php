<?php

namespace App\Event\Listener;

use App\Monitor\Event\MonitorFinishedEvent;
use App\Repository\EventRepository;
use App\Service\EventFactory;

/**
 * MonitorFinishedListener
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorFinishedListener
{
    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * @var EventFactory
     */
    private $factory;

    /**
     * MonitorFinishedListener constructor.
     *
     * @param EventRepository $repository
     * @param EventFactory    $factory
     */
    public function __construct(EventRepository $repository, EventFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param MonitorFinishedEvent $event
     *
     * @throws \Exception
     */
    public function onMonitorFinished(MonitorFinishedEvent $event): void
    {
        $monitorUnit = $event->getMonitorUnit();

        if (!$monitorUnit->isDeactivated()) {
            $event = $this->factory->buildEventByMonitorUnit($monitorUnit);
            $this->repository->save($event);
        }
    }
}