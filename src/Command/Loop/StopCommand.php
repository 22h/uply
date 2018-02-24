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
 * StopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StopCommand extends ContainerAwareCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const COMMAND_NAME = 'monitor:loop:stop';

    /**
     * @var MonitoringLoopService
     */
    private $monitoringLoopService;

    /**
     * StopCommand constructor.
     *
     * @param MonitoringLoopService $eventService
     */
    public function __construct(MonitoringLoopService $eventService)
    {
        $this->monitoringLoopService = $eventService;

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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        if ($this->monitoringLoopService->isLoopProcessRunning()) {
            $symfonyStyle->success('Stop loop now. If you have set up a cron job, the loop will restart immediately.');

            $this->monitoringLoopService->addExitEvent();
        } else {
            $symfonyStyle->warning('Loop is already stopped');
        }
    }
}
