<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\DBAL\Types\Type;

/**
 * EventRepository
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class EventRepository extends AbstractRepository
{

    /**
     * @return null|Event
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNextUnit(): ?Event
    {
        $qb = $this->createQueryBuilder('event');
        $qb->where($qb->expr()->lt('event.nextCheck', ':nextCheck'));
        $qb->orderBy('event.nextCheck', 'ASC');
        $qb->setParameter('nextCheck', new \DateTime(), Type::DATETIME);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int    $unitId
     * @param string $unitType
     */
    public function deleteByUnit(int $unitId, string $unitType)
    {
        $qb = $this->createQueryBuilder('event');
        $qb->delete();
        $qb->where($qb->expr()->eq('event.unitId', ':unitId'));
        $qb->andWhere($qb->expr()->eq('event.unitType', ':unitType'));
        $qb->setParameter('unitId', $unitId, Type::INTEGER);
        $qb->setParameter('unitType', $unitType, Type::STRING);
        $qb->getQuery()->execute();
    }
}
