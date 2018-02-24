<?php

namespace App\Repository\Unit;

use App\Entity\Unit\UnitInterface;

/**
 * UnitRepositoryInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface UnitRepositoryInterface
{

    /**
     * @param int $id
     */
    public function setAsTriggered(int $id): void;

    /**
     * @param int $id
     */
    public function removeTriggered(int $id): void;

    /**
     * @param int $lastCheckedId
     * @param int $limit
     *
     * @return mixed
     */
    public function getUnitEntriesOrderedById(int $lastCheckedId, int $limit = 10);

    /**
     * @param int $id
     *
     * @return UnitInterface|null
     */
    public function findUnitById(int $id): ?UnitInterface;
}