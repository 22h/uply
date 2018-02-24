<?php

namespace App\Unit;

use App\Entity\Unit\UnitInterface;
use App\Repository\Unit\UnitRepositoryInterface;

/**
 * AbstractUnitService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractUnitService implements UnitServiceInterface
{

    /**
     * @var UnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var ScrutinizerInterface
     */
    protected $scrutinizer;

    /**
     * @param int $id
     *
     * @return UnitInterface
     * @throws \Exception
     */
    public function scrutinize(int $id): UnitInterface
    {
        $unit = $this->repository->findUnitById($id);

        $class = $this->getEntityClass();
        if ($unit instanceof $class && !$unit->isDeactivated()) {
            $this->scrutinizer->scrutinize($unit);
        }

        return $unit;
    }

    /**
     * @return UnitRepositoryInterface
     */
    public function getRepository(): UnitRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @return string
     */
    public function getUnitIdent(): string
    {
        $class = $this->getEntityClass();

        return $class::getIdent();
    }

}