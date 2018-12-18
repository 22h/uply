<?php

namespace App\Job;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Scrutinizer\Exception\UnitDeactivatedException;
use App\Scrutinizer\Exception\UnitNotFoundException;
use App\Scrutinizer\ScrutinizerChain;
use App\Scrutinizer\ScrutinizerService;

/**
 * MonitorService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobService
{
    /**
     * @var JobRepository
     */
    private $repository;

    /**
     * @var ScrutinizerService
     */
    private $scrutinizerService;

    /**
     * @var ScrutinizerChain
     */
    private $scrutinizerChain;
    /**
     * @var JobFactory
     */
    private $jobFactory;

    /**
     * @param JobRepository      $repository
     * @param JobFactory         $jobFactory
     * @param ScrutinizerService $scrutinizerService
     * @param ScrutinizerChain   $scrutinizerChain
     */
    public function __construct(
        JobRepository $repository,
        JobFactory $jobFactory,
        ScrutinizerService $scrutinizerService,
        ScrutinizerChain $scrutinizerChain
    ) {
        $this->repository = $repository;
        $this->scrutinizerService = $scrutinizerService;
        $this->scrutinizerChain = $scrutinizerChain;
        $this->jobFactory = $jobFactory;
    }

    /**
     * @param int $jobId
     *
     * @throws \Exception
     */
    public function executeByJobId(int $jobId): void
    {
        $job = $this->repository->find($jobId);
        if (!($job instanceof Job)) {
            throw new \Exception(sprintf('no job found with id (%d)', $jobId));
        }

        $this->executeByJob($job);
    }

    /**
     * @param Job $job
     */
    public function executeByJob(Job $job): void
    {
        try {
            $nextCheck = $this->scrutinizerService->scrutinizeJob($job);

            $job->setNextCheck($nextCheck);
            $this->repository->save($job);
        } catch (UnitDeactivatedException $exception) {
            $this->repository->remove($job);
        } catch (UnitNotFoundException $exception) {
            $this->repository->remove($job);
        }
    }

    /**
     * @return Job[]
     */
    public function findNextUnits(): array
    {
        return $this->repository->findNextUnits();
    }

    /**
     * @throws \Exception
     */
    public function syncUnitsInEvents()
    {
        $scrutinizerIdentities = $this->scrutinizerChain->getIdentifier();
        foreach ($scrutinizerIdentities as $scrutinizerIdentity) {
            $this->syncUnitsInEventsByIdent($scrutinizerIdentity);
        }
    }

    /**
     * @param Job $job
     */
    public function rescheduleJob(Job $job): void
    {
        $job->setNextCheck(new \DateTime('+1 minutes'));
        $this->repository->save($job);
    }

    /**
     * @param string $identity
     */
    private function syncUnitsInEventsByIdent(string $identity)
    {
        $scrutinizer = $this->scrutinizerChain->getScrutinizer($identity);
        $repository = $scrutinizer->getRepository();
        $units = $repository->findMissingUnitsInJobQueue();

        foreach ($units as $unit) {
            $repository->save($this->jobFactory->createJob($unit->getId(), $identity));
        }
    }
}