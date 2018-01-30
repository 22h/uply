<?php

namespace App\Monitor\Unit;

use App\Entity\Unit\UnitInterface;
use App\Monitor\Event\MonitorNotifyEvent;
use App\Monitor\UnitNotFoundException;
use App\Monitor\UnitParameterBagFactory;
use App\Repository\Unit\AbstractMonitorRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * AbstractUnitCheck
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractUnitCheck implements UnitCheckInterface
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
     * @var AbstractMonitorRepository
     */
    protected $repository;

    /**
     * AbstractBaseUnit constructor.
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
     * @param int $id
     *
     * @return UnitInterface
     * @throws UnitNotFoundException
     * @throws \Exception
     */
    public function getUnit(int $id): UnitInterface
    {
        if (!($this->repository instanceof AbstractMonitorRepository)) {
            throw new \Exception('add an repository in your monitoring unit');
        }

        /** @var UnitInterface $unit */
        $unit = $this->repository->find($id);
        if (!($this->isInstanceOfEntity($unit))) {
            throw new UnitNotFoundException('no '.get_class($unit).' entity with id '.$id.' found.');
        }

        return $unit;
    }

    /**
     * @param UnitInterface $unit
     *
     * @return bool
     */
    public function isInstanceOfEntity(UnitInterface $unit): bool
    {
        return $this->checkInstanceOfEntity($unit, $this->entityClass());
    }

    /**
     * handle
     *
     * @param UnitInterface $unit
     *
     * @throws \Exception
     */
    public function handle(UnitInterface $unit): void
    {
        if (!($this->isInstanceOfEntity($unit))) {
            throw new \Exception();
        }
    }

    /**
     * @param $unit
     * @param $title
     * @param $message
     * @param $alert
     */
    protected function triggerNotification($unit, $title, $message, $alert): void
    {
        $parameterBag = $this->parameterBagFactory->createParameterBag(
            $unit,
            $message,
            $title,
            $alert
        );

        $event = new MonitorNotifyEvent($parameterBag);
        $this->eventDispatcher->dispatch(MonitorNotifyEvent::NAME, $event);
    }

    /**
     * @param UnitInterface $unit
     * @param string        $entityClass
     *
     * @return bool
     */
    protected function checkInstanceOfEntity(UnitInterface $unit, string $entityClass): bool
    {
        return ($unit instanceof $entityClass);
    }

}