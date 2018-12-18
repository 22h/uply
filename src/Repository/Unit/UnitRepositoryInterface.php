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
     * @return array
     */
    public function findMissingUnitsInJobQueue(): array;

    /**
     * @param int $id
     *
     * @return UnitInterface|null
     */
    public function findUnitById(int $id): ?UnitInterface;

    /**
     * @return mixed
     */
    public function findTriggeredUnits();

    /**
     * @return int
     */
    public function countUnits(): int;
}