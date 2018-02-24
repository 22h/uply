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
     * @param string $unitIdent
     *
     * @return int
     */
    public function deleteByUnitTypeAndId(int $unitId, string $unitIdent): int
    {
        $qb = $this->createQueryBuilder('event');
        $qb->delete();
        $qb->where($qb->expr()->eq('event.unitId', ':unitId'));
        $qb->andWhere($qb->expr()->eq('event.unitIdent', ':unitIdent'));
        $qb->setParameter('unitId', $unitId, Type::INTEGER);
        $qb->setParameter('unitIdent', $unitIdent, Type::STRING);
        $query = $qb->getQuery();
        return (int)$query->execute();
    }
}
