<?php

namespace App\Repository\Unit;

use App\Repository\AbstractRepository;
use Doctrine\DBAL\Types\Type;

/**
 * AbstractMonitorRepository
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractMonitorRepository extends AbstractRepository implements MonitorRepositoryInterface
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
}