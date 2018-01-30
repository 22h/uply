<?php

namespace App\Monitor\Unit;


use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitNotFoundException;

interface UnitCheckInterface
{

    /**
     * @param int $id
     *
     * @return UnitInterface
     * @throws UnitNotFoundException
     */
    public function getUnit(int $id): UnitInterface;

    /**
     * @param UnitInterface $unit
     */
    public function handle(UnitInterface $unit): void;

    /**
     * @param UnitInterface $unit
     *
     * @return bool
     */
    public function isInstanceOfEntity(UnitInterface $unit): bool;

    /**
     * @return string
     */
    public function entityClass(): string;
}