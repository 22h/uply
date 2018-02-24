<?php

namespace App\Monitor;

use App\Unit\UnitServiceInterface;

/**
 * MonitorUnitChain
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class UnitServiceChain
{

    /**
     * @var UnitServiceInterface[]
     */
    private $unitServices = [];

    /**
     * @param UnitServiceInterface $unit
     * @param string               $identifier
     */
    public function addUnitService(UnitServiceInterface $unit, string $identifier): void
    {
        $this->unitServices[$identifier] = $unit;
    }

    /**
     * @param string $identifier
     *
     * @return UnitServiceInterface
     * @throws \Exception
     */
    public function getUnitService(string $identifier): UnitServiceInterface
    {
        if (array_key_exists($identifier, $this->unitServices)) {
            return $this->unitServices[$identifier];
        }

        throw new \Exception(
            sprintf('No monitor unit service for ident "%s" found', $identifier)
        );
    }

    /**
     * @return array
     */
    public function getIdentifier(): array
    {
        return array_keys($this->unitServices);
    }
}