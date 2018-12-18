<?php

namespace App\Command;

use App\Job\JobService;
use App\Service\AliveService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * JobLoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobLoopCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public static $defaultName = 'job:loop';

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @var bool
     */
    private $shouldStop = false;

    /**
     * @var string
     */
    private $projectDir;
    /**
     * @var AliveService
     */
    private $aliveService;

    /**
     * @param JobService   $jobService
     * @param AliveService $aliveService
     * @param string       $projectDir
     */
    public function __construct(JobService $jobService, AliveService $aliveService, string $projectDir)
    {
        $this->jobService = $jobService;
        $this->projectDir = $projectDir;
        $this->aliveService = $aliveService;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        pcntl_signal(SIGTERM, [$this, 'stopCommand']);
        pcntl_signal(SIGINT, [$this, 'stopCommand']);

        while (true) {
            pcntl_signal_dispatch();
            $this->checkAbortSettings();

            $jobs = $this->jobService->findNextUnits();
            foreach ($jobs as $job) {
                $this->jobService->rescheduleJob($job);
                $command = 'php '.$this->projectDir.'/bin/console job:process '.$job->getId(
                    ).' > /dev/null 2>/dev/null &';

                $this->logger->debug(sprintf('execute command: (%s)', $command));
                shell_exec($command);
            }
            if (count($jobs) < 1) {
                $this->logger->debug('sleep');
                sleep(1);
            }

            $this->aliveService->heartbeat();
        }
    }

    public function stopCommand(): void
    {
        $this->logger->debug('get pcntl_signal to stop command');
        $this->shouldStop = true;
    }

    private function checkAbortSettings(): void
    {
        if ($this->shouldStop) {
            exit(1);
        }

        if (memory_get_usage(true) > $this->getAllowedMemoryLimitInBytes() * 0.9) {
            $this->logger->debug(
                sprintf(
                    'memory usage is over %s of allowed; limit is %s; usage is %s',
                    '90%',
                    $this->getAllowedMemoryLimitInBytes() * 0.9,
                    memory_get_usage(true)
                )
            );
            exit(1);
        }
    }

    /**
     * @return int
     */
    private function getAllowedMemoryLimitInBytes(): int
    {
        $memoryLimit = ini_get('memory_limit');
        if ($memoryLimit == '-1') {
            return 32 * 1024 * 1024;
        }

        $value = trim($memoryLimit);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int)$value;
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}