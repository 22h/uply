<?php

namespace App\Repository;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityRepository;

/**
 * AbstractRepository
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class AbstractRepository extends EntityRepository
{

    /**
     * save
     *
     * @param mixed $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * getReference
     *
     * @param string $entityName
     * @param mixed  $id
     *
     * @return object|Proxy
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference(string $entityName, $id): object
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }

    /**
     * @param $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

}