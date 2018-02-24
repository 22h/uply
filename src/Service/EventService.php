<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;

/**
 * EventService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class EventService
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
     * EventService constructor.
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
     * @return Event|null
     */
    public function findNextUnit(): ?Event
    {
        return $this->repository->findNextUnit();
    }

    /**
     * @param int    $unitId
     * @param string $unitIdent
     *
     * @return int
     */
    public function deleteByUnitTypeAndId(int $unitId, string $unitIdent): int
    {
        return $this->repository->deleteByUnitTypeAndId($unitId, $unitIdent);
    }

    /**
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function storeEvent(Event $event)
    {
        $this->repository->save($event);
    }

    /**
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeEvent(Event $event): void
    {
        $this->repository->remove($event);
    }
}