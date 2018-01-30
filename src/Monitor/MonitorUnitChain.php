<?php

namespace App\Monitor;

use App\Monitor\Unit\UnitCheckInterface;

/**
 * MonitorUnitChain
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorUnitChain
{

    /**
     * @var UnitCheckInterface[]
     */
    private $monitorUnits = [];

    /**
     * @param UnitCheckInterface $unit
     * @param string             $identifier
     */
    public function addMonitorUnit(UnitCheckInterface $unit, string $identifier): void
    {
        $this->monitorUnits[$identifier] = $unit;
    }

    /**
     * @param string $identifier
     *
     * @return UnitCheckInterface
     * @throws \Exception
     */
    public function getMonitorUnit(string $identifier): UnitCheckInterface
    {
        if (array_key_exists($identifier, $this->monitorUnits)) {
            return $this->monitorUnits[$identifier];
        }

        throw new \Exception(
            sprintf('No monitor unit for ident "%s" found', $identifier)
        );
    }

    /**
     * @return array
     */
    public function getMonitorUnitIds(): array
    {
        return array_keys($this->monitorUnits);
    }
}