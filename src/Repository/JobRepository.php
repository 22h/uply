<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\DBAL\Types\Type;

/**
 * JobRepository
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobRepository extends AbstractRepository
{

    /**
     * @return Job[]
     */
    public function findNextUnits(): array
    {
        $qb = $this->createQueryBuilder('job');
        $qb->where($qb->expr()->lt('job.nextCheck', ':nextCheck'));
        $qb->orWhere($qb->expr()->isNull('job.nextCheck'));
        $qb->orderBy('job.nextCheck', 'ASC');
        $qb->setParameter('nextCheck', new \DateTime(), Type::DATETIME);
        $qb->setMaxResults(10);

        $jobs = $qb->getQuery()->getResult();
        if(!is_array($jobs)) {
            return [];
        }

        return $jobs;
    }
}
