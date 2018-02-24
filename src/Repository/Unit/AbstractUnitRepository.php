<?php

namespace App\Repository\Unit;

use App\Entity\Unit\UnitInterface;
use App\Repository\AbstractRepository;
use Doctrine\DBAL\Types\Type;

/**
 * AbstractMonitorRepository
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractUnitRepository extends AbstractRepository implements UnitRepositoryInterface
{

    /**
     * @param int $id
     */
    public function setAsTriggered(int $id): void
    {
        $qb = $this->createQueryBuilder('unit');
        $qb->update();
        $qb->set('unit.triggered', ':time');
        $qb->where('unit.id = :id');
        $qb->setParameter('id', $id);
        $qb->setParameter('time', new \DateTime(), Type::DATETIME);
        $qb->getQuery()->execute();
    }

    /**
     * @param int $id
     */
    public function removeTriggered(int $id): void
    {
        $qb = $this->createQueryBuilder('unit');
        $qb->update();
        $qb->set('unit.triggered', 'null');
        $qb->where('unit.id = :id');
        $qb->setParameter('id', $id);
        $qb->getQuery()->execute();
    }

    /**
     * @param int $lastCheckedId
     * @param int $limit
     *
     * @return mixed
     */
    public function getUnitEntriesOrderedById(int $lastCheckedId, int $limit = 10)
    {
        $qb = $this->createQueryBuilder('unit');
        $qb->select()
            ->where($qb->expr()->eq('unit.deactivated', 0))
            ->andWhere($qb->expr()->gt('unit.id', ':lastCheckedId'))
            ->orderBy('unit.id', 'ASC')
            ->setMaxResults($limit)
            ->setParameter('lastCheckedId', $lastCheckedId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return UnitInterface|null
     */
    public function findUnitById(int $id): ?UnitInterface
    {
        $object = $this->find($id);
        if($object instanceof UnitInterface) {
            return $object;
        }

        return null;
    }
}