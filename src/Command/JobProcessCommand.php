<?php

namespace App\Command;

use App\Job\JobService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * JobProcessCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobProcessCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public static $defaultName = 'job:process';

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
    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'job id');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->jobService->executeByJobId((int)$input->getArgument('id'));
    }
}