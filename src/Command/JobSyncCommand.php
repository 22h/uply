<?php

namespace App\Command;

use App\Job\JobService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * JobSyncCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobSyncCommand extends Command
{
    public static $defaultName = 'job:sync';

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @param JobService $jobService
     */
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->jobService->syncUnitsInEvents();
    }
}