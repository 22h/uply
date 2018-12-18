<?php

namespace App\Service;

use App\Scrutinizer\ScrutinizerChain;

/**
 * StatusService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusService
{
    /**
     * @var ScrutinizerChain
     */
    private $scrutinizerChain;

    /**
     * @var AliveService
     */
    private $aliveService;

    /**
     * @param ScrutinizerChain $scrutinizerChain
     * @param AliveService     $aliveService
     */
    public function __construct(ScrutinizerChain $scrutinizerChain, AliveService $aliveService)
    {
        $this->scrutinizerChain = $scrutinizerChain;
        $this->aliveService = $aliveService;
    }

    /**
     * @return bool
     */
    public function isLoopProcessRunning(): bool
    {
        return $this->aliveService->isStillAlive();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllTriggeredUnits(): array
    {
        $unitIdentities = $this->scrutinizerChain->getIdentifier();
        $output = [];
        foreach ($unitIdentities as $unitIdent) {
            $output[$unitIdent] = $this->getAllTriggeredUnitsByIdent($unitIdent);
        }

        return $output;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function countAllUnits(): array
    {
        $unitIdentities = $this->scrutinizerChain->getIdentifier();
        $output = [];
        foreach ($unitIdentities as $unitIdent) {
            $output[$unitIdent] = $this->countUnitsByIdent($unitIdent);
        }

        return $output;
    }

    /**
     * @return array
     */
    public function getAvailableUnits(): array
    {
        return $this->scrutinizerChain->getIdentifier();
    }

    /**
     * @param string $ident
     *
     * @return int
     * @throws \Exception
     */
    private function countUnitsByIdent(string $ident): int
    {
        $scrutinizer = $this->scrutinizerChain->getScrutinizer($ident);
        $repository = $scrutinizer->getRepository();

        return $repository->countUnits();
    }

    /**
     * @param string $ident
     *
     * @return array
     * @throws \Exception
     */
    private function getAllTriggeredUnitsByIdent(string $ident): array
    {
        $scrutinizer = $this->scrutinizerChain->getScrutinizer($ident);
        $repository = $scrutinizer->getRepository();

        return $repository->findTriggeredUnits();
    }
}