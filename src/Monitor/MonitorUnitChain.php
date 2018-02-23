<?php

namespace App\Monitor;

use App\Entity\Unit\UnitInterface;
use App\Monitor\Unit\UnitCheckInterface;
use App\Repository\Unit\MonitorRepositoryInterface;

/**
 * MonitorUnitChain
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorUnitChain
{

    const MONITORING_UNIT_KEY_ENTITY     = 'entity';
    const MONITORING_UNIT_KEY_REPOSITORY = 'repository';
    const MONITORING_UNIT_KEY_SERVICE    = 'service';
    const MONITORING_UNIT_KEY_ENABLED    = 'enabled';

    /**
     * @var array
     */
    private $monitorUnits;

    /**
     * MonitorUnitChain constructor.
     *
     * @param array $monitorUnits
     *
     * @throws \Exception
     */
    public function __construct(array $monitorUnits)
    {
        foreach ($monitorUnits as $ident => $monitorUnit) {
            $this->checkConfigEntry($ident, $monitorUnit);
            $this->monitorUnits[$ident] = $monitorUnit;
            if (array_key_exists(self::MONITORING_UNIT_KEY_ENABLED, $monitorUnit)
                && $monitorUnit[self::MONITORING_UNIT_KEY_ENABLED] == true) {
                $this->monitorUnits[$ident] = $monitorUnit;
            }
        }
    }

    /**
     * @param string $ident
     *
     * @return string
     * @throws \Exception
     */
    public function getEntityClassByIdent(string $ident)
    {
        if (!$this->isIdentAvailable($ident)) {
            throw new \Exception('monitoring units with ident: '.$ident.' is not configured.');
        }

        return (string)$this->monitorUnits[$ident][self::MONITORING_UNIT_KEY_ENTITY];
    }

    /**
     * @param string $ident
     *
     * @return string
     * @throws \Exception
     */
    public function getRepositoryClassByIdent(string $ident)
    {
        if (!$this->isIdentAvailable($ident)) {
            throw new \Exception('monitoring units with ident: '.$ident.' is not configured.');
        }

        return (string)$this->monitorUnits[$ident][self::MONITORING_UNIT_KEY_REPOSITORY];
    }

    /**
     * @param string $ident
     *
     * @return string
     * @throws \Exception
     */
    public function getMonitoringClassByIdent(string $ident): string
    {
        if (!$this->isIdentAvailable($ident)) {
            throw new \Exception('monitoring units with ident: '.$ident.' is not configured.');
        }

        return (string)$this->monitorUnits[$ident][self::MONITORING_UNIT_KEY_SERVICE];
    }

    /**
     * @param string $ident
     *
     * @return bool
     */
    public function isIdentAvailable(string $ident): bool
    {
        return (array_key_exists($ident, $this->monitorUnits));
    }

    /**
     * @return array
     */
    public function getMonitorUnitIdents(): array
    {
        return array_keys($this->monitorUnits);
    }

    /**
     * @param string $ident
     * @param array  $configuration
     *
     * @throws \Exception
     */
    private function checkConfigEntry(string $ident, array $configuration)
    {
        if (!array_key_exists(self::MONITORING_UNIT_KEY_ENTITY, $configuration)
            || !is_subclass_of($configuration[self::MONITORING_UNIT_KEY_ENTITY], UnitInterface::class)) {
            throw new \Exception(
                'monitoring units, ident: '.$ident
                .' entity is not set or no instance of '.UnitInterface::class
            );
        }

        if (!array_key_exists(self::MONITORING_UNIT_KEY_REPOSITORY, $configuration)
            || !is_subclass_of(
                $configuration[self::MONITORING_UNIT_KEY_REPOSITORY],
                MonitorRepositoryInterface::class
            )) {
            throw new \Exception(
                'monitoring units, ident: '.$ident
                .' repository is not set or no instance of '.MonitorRepositoryInterface::class
            );
        }

        if (!array_key_exists(self::MONITORING_UNIT_KEY_SERVICE, $configuration)
            || !is_subclass_of(
                $configuration[self::MONITORING_UNIT_KEY_SERVICE],
                UnitCheckInterface::class
            )) {
            throw new \Exception(
                'monitoring units, ident: '.$ident
                .' service is not set or no instance of '.UnitCheckInterface::class
            );
        }
    }
}