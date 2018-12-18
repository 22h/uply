<?php

namespace App\Repository\Unit;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
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
     * @return UnitInterface[]
     */
    public function findMissingUnitsInJobQueue(): array
    {
        $unitIdent = (string)call_user_func($this->getClassName().'::getIdent');

        $qb2 = $this->_em->createQueryBuilder();
        $qb2->select('j.unitId')
            ->from('App\Entity\Job', 'j')
            ->where('j.unitIdent = :unitIdent');

        $qb = $this->createQueryBuilder('unit');
        $qb->select()
            ->where($qb->expr()->eq('unit.deactivated', 0))
            ->andWhere(
                $qb->expr()->notIn('unit.id', $qb2->getDQL())
            )
            ->setParameter('unitIdent', $unitIdent);

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
        if ($object instanceof UnitInterface) {
            return $object;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function findTriggeredUnits()
    {
        $qb = $this->createQueryBuilder('unit');
        $qb->select()
            ->where($qb->expr()->isNotNull('unit.actualLevel'))
            ->andWhere($qb->expr()->neq('unit.actualLevel', ':actualLevelSuccess'))
            ->andWhere($qb->expr()->eq('unit.deactivated', 0))
            ->setParameter('actualLevelSuccess', NotificationData::LEVEL_SUCCESS);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function countUnits(): int
    {
        return $this->count([]);
    }
}