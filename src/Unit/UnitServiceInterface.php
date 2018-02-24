<?php

namespace App\Unit;

use App\Entity\Unit\UnitInterface;
use App\Repository\Unit\UnitRepositoryInterface;

/**
 * UnitServiceInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface UnitServiceInterface
{

    /**
     * @param int $id
     *
     * @return UnitInterface
     */
    public function scrutinize(int $id): UnitInterface;

    /**
     * @return UnitRepositoryInterface
     */
    public function getRepository(): UnitRepositoryInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;

    /**
     * @return string
     */
    public function getUnitIdent(): string;
}