<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitServiceChain;
use App\Repository\EventRepository;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var UnitServiceChain
     */
    private $unitChain;

    /**
     * EventService constructor.
     *
     * @param EventRepository    $repository
     * @param EventFactory       $factory
     * @param UnitServiceChain   $unitChain
     */
    public function __construct(
        EventRepository $repository,
        EventFactory $factory,
        UnitServiceChain $unitChain
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->unitChain = $unitChain;
    }

    /**
     * @return Event|null
     * @throws \Doctrine\ORM\NonUniqueResultException
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

    /**
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function syncUnitsInEvents(OutputInterface $output)
    {
        $unitIdentities = $this->unitChain->getIdentifier();

        foreach ($unitIdentities as $unitIdent) {
            $this->syncUnitsInEventsByIdent($unitIdent, $output);
        }
    }

    /**
     * @param string          $ident
     * @param OutputInterface $output
     * @param int|null        $lastId
     *
     * @throws \Exception
     */
    private function syncUnitsInEventsByIdent(string $ident, OutputInterface $output, ?int $lastId = null)
    {
        $unitService = $this->unitChain->getUnitService($ident);
        $repository = $unitService->getRepository();

        if(is_null($lastId)) {
            $lastId = 0;
        }

        $entries = $repository->getUnitEntriesOrderedById($lastId, 10);

        if(empty($entries)) {
            return;
        }

        $ids = [];
        foreach ($entries as $entry) {
            /** @var UnitInterface $entry */
            $ids[] = $entry->getId();
        }

        $eventUnitIds = $this->repository->findUnitIdsInEventsByUnitIds($ids, $ident);

        foreach ($ids as $key => $id) {
            if (!in_array($id, $eventUnitIds)) {
                $output->writeln('Entry "'.$id.'" from "'.$ident.'" is missing');
                $event = $this->factory->buildEvent(
                    (new \DateTime()),
                    $id,
                    $ident
                );
                $this->storeEvent($event);
            }
        }

        $this->syncUnitsInEventsByIdent($ident, $output, end($ids));
    }
}