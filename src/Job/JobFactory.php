<?php

namespace App\Job;

use App\Entity\Job;

/**
 * JobFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobFactory
{

    /**
     * @param int    $unitId
     * @param string $unitIdent
     *
     * @return Job
     */
    public function createJob(int $unitId, string $unitIdent)
    {
        return $this->newInstace()->setUnitId($unitId)->setUnitIdent($unitIdent)->setNextCheck(new \DateTime());
    }

    /**
     * @return Job
     */
    private function newInstace(): Job
    {
        return new Job();
    }
}