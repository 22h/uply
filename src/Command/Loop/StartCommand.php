<?php

namespace App\Command\Loop;

use App\Service\MonitoringLoopService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * StartLoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StartCommand extends ContainerAwareCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const COMMAND_NAME = 'monitor:loop:start';

    /**
     * @var MonitoringLoopService
     */
    private $monitoringLoopService;

    /**
     * CheckLoopCommand constructor.
     *
     * @param MonitoringLoopService $monitoringLoopService
     */
    public function __construct(MonitoringLoopService $monitoringLoopService)
    {
        $this->monitoringLoopService = $monitoringLoopService;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        if (!$this->monitoringLoopService->isLoopProcessRunning()) {
            $message = 'restart loop now';
            $symfonyStyle->warning($message);
            $this->logger->info($message);

            $this->monitoringLoopService->startLoopProcessNow();
        } else {
            $symfonyStyle->success('loop is already running');
        }
    }
}
